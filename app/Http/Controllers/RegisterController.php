<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::all();
        return view('add-users', [
            'classes' => $classes
        ]);
    }

    public function user(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'snils' => $request->snils,
            'inn' => $request->inn,
            'passport_data' => $request->passport_data,
            'birth_date' => $request->birth_date,
            'post' => $request->post,
            'phone' => $request->phone,
            'address' => $request->address,
            'education' => $request->education,
            'experience' => $request->experience,
        ]);

        return redirect()->back();
    }

    public function student(Request $request)
    {
        Student::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'class_id' => $request->class,
            'snils' => $request->snils,
            'inn' => $request->inn,
            'passport_data' => $request->passport_data,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->back();
    }

    public function guardian(Request $request)
    {
        Guardian::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'job' => $request->job,
            'status' => $request->status,
        ]);

        return redirect()->back();
    }
}
