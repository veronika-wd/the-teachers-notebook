<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardianRegisterRequest;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();
        $subjects = Subject::orderByDesc('id')->get();
        return view('add-users', [
            'classes' => $classes,
            'subjects' => $subjects,
        ]);
    }

    public function user(UserRegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'snils' => $request->snils_user,
            'inn' => $request->inn_user,
            'passport_data' => $request->passport_data_user,
            'birth_date' => $request->birth_date,
            'post' => $request->post,
            'phone' => $request->phone,
            'address' => $request->address,
            'education' => $request->education,
            'experience' => $request->experience,
        ]);

        return redirect()->back();
    }

    public function student(StudentRegisterRequest $request)
    {
//        dd($request->class);
        Student::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'class' => $request->class,
            'snils' => $request->snils_student,
            'inn' => $request->inn_student,
            'passport_data' => $request->passport_data_student,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->back();
    }

    public function guardian(GuardianRegisterRequest $request)
    {
        Guardian::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'job' => $request->job,
            'status' => $request->status,
            'snils' => $request->snils_guardian,
            'inn' => $request->inn_guardian,
            'passport_data' => $request->passport_data_guardian,
        ]);

        return redirect()->back();
    }

    public function createSubject(Request $request)
    {
        Subject::create([
            'name' => $request->name,
        ]);
        return redirect()->back();
    }

    public function deleteSubject(Subject $subject)
    {
        $subject->delete();
        return redirect()->back();
    }

    public function createClass(Request $request)
    {
        SchoolClass::create([
            'name' => $request->name,
        ]);
        return redirect()->back();
    }

    public function deleteClass(SchoolClass $class)
    {
        $class->delete();
        return redirect()->back();
    }


}
