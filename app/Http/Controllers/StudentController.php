<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Http\Services\UploadService;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct(
        protected UploadService $uploadService,
    )
    {
    }

    public function index() {
        $classes = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();
        $students = [];
        foreach ($classes as $class) {
            $students[$class->name] = Student::where('class', $class->id)->orderBy('surname')->get();
        }
        return view('students.students', [
            'students' => $students,
            'classes' => $classes,
        ]);
    }

    public function show(Student $student)
    {
        $guardiansAll = Guardian::all();
        $guardians = $student->guardians;
        $achievements = $student->achievements;
        $classes = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();

        return view('students.show', [
            'student' => $student,
            'guardians' => $guardians,
            'achievements' => $achievements,
            'guardiansAll' => $guardiansAll,
            'classes' => $classes,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $student->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'class' => $request->class,
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

    public function promoteAll()
    {
        DB::beginTransaction();

        try {
            $classes = SchoolClass::with('students')
                ->withCount('students')
                ->get();

            $sortedClasses = $classes->sortByDesc(function (SchoolClass $class) {
                preg_match('/^(\d+)/', $class->name, $matches);
                return (int)($matches[1] ?? 0);
            });

            foreach ($sortedClasses as $class) {
                preg_match('/^(\d+)([А-Я]+)?/', $class->name, $matches);
                $grade = (int)$matches[1];
                $letter = isset($matches[2]) ? mb_substr($class->name, mb_strlen($matches[1])) : null;

                if ($grade === 11) {
                    $class->students()->delete();
                    $class->delete();
                    continue;
                }

                $nextGrade = $grade + 1;
                $nextClassName = $letter ? $nextGrade . $letter : $nextGrade;

                $targetClass = SchoolClass::firstWhere('name', $nextClassName);

                if (!$targetClass) {
                    $targetClass = SchoolClass::create(['name' => $nextClassName]);
                }

                if ($class->students_count > 0) {
                    Student::where('class', $class->id)->update(['class' => $targetClass->id]);
                }

                $class->delete();
            }

            SchoolClass::create([
                'name' => '1',
            ]);

            DB::commit();

            return to_route('students.index');

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Student $student)
    {
        $student->guardians()->detach();
        $student->delete();
        return to_route('students.index');
    }

    public function storeGuardian(Student $student, Request $request)
    {
        $student->guardians()->attach($request->guardian);

        return redirect()->back();
    }

    public function removeGuardian(Guardian $guardian, Student $student)
    {
        $guardian->students()->detach($student->id);

        return redirect()->back();
    }

}
