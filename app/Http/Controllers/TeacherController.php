<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherResource;
use App\Http\Services\UploadService;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(
        protected UploadService $uploadService
    )
    {
    }

    public function index()
    {
        $teacher = auth()->user();
        return view('cabinet.cabinet', [
            'teacher' => $teacher,
        ]);
    }

    public function update(Request $request)
    {
        $teacher = auth()->user();

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'snils' => $request->snils,
            'inn' => $request->inn,
            'passport_data' => $request->passport_data,
            'phone' => $request->phone,
            'address' => $request->address,
            'education' => $request->education,
            'experience' => $request->experience,
            'birth_date' => $request->birth_date,
        ]);

        return redirect()->back();
    }
    public function getTeachers()
    {
        $teachers = User::all();

        return response()->json(TeacherResource::collection($teachers));
    }

    public function achievements()
    {
        $achievements = auth()->user()->achievements;

        return view('cabinet.achievements', [
            'achievements' => $achievements,
        ]);
    }

    public function storeAchievement(Request $request)
    {
        $user = auth()->user();

        $user->achievements()->create([
            'name' => $request->name,
            'path' => $this->uploadService->uploadFileAchievement($request),
        ]);

        return redirect()->back();
    }
}
