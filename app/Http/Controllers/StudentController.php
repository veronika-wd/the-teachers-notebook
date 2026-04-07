<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Http\Services\UploadService;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentController extends Controller
{
    public function __construct(
        protected UploadService $uploadService,
    )
    {
    }

    public function index() {
        $classes = SchoolClass::all();
        $students = [];
        foreach ($classes as $class) {
            $students[$class->name] = Student::where('class', $class->id)->get();
        }
        return view('students.students', [
            'students' => $students,
            'classes' => $classes,
        ]);
    }

    public function show(Student $student)
    {
        $guardians = $student->guardians;
        $achievements = $student->achievements;

        return view('students.show', [
            'student' => $student,
            'guardians' => $guardians,
            'achievements' => $achievements,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $student->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'snils' => $request->snils,
            'inn' => $request->inn,
            'passport_data' => $request->passport_data,
            'birth_date' => $request->birth_date,
            'status' => $request->status,
            'address' => $request->address,
        ]);

        return redirect()->back();
    }

    public function database()
    {
        return view('database');
    }

    public function getStudents()
    {
        $students = Student::all();

        return response()->json(StudentResource::collection($students));
    }

    public function storeAchievement(Request $request, Student $student)
    {
        $student->achievements()->create([
            'name' => $request->name,
            'path' => $this->uploadService->uploadFileAchievement($request),
        ]);

        return redirect()->back();
    }

}
