<?php

namespace App\Http\Controllers;

use App\Http\Resources\GuardianResource;
use App\Http\Resources\TeacherResource;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    public function getGuardians()
    {
        $guardians = Guardian::all();

        return response()->json(GuardianResource::collection($guardians));
    }

    public function show(Guardian $guardian)
    {
        $studentsAll = Student::all();
        $students = $guardian->students;


        return view('guardians.show', [
            'guardian' => $guardian,
            'students' => $students,
            'studentsAll' => $studentsAll,
        ]);
    }

    public function update(Request $request, Guardian $guardian)
    {
        $guardian->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
            'job' => $request->job,
        ]);

        return redirect()->back();
    }

    public function store(Request $request, Guardian $guardian)
    {
        $guardian->students()->attach($request->student);

        return redirect()->back();
    }
}
