<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentActivity;
use Illuminate\Http\Request;

class ClassActivityController extends Controller
{
    /**
     * Страница со списком классов + ссылки на кружки
     */
    public function index()
    {
        // withCount('students') добавляет атрибут students_count к каждому классу
        $allClasses = SchoolClass::withCount('students')->get();

        // Сортировка по номеру класса (1, 2, ... 5Б, 9, 10, 11)
        $classes = $allClasses->sortBy(function ($class) {
            preg_match('/^\d+/', $class->name, $matches);
            $number = (int)($matches[0] ?? 0);
            return sprintf('%02d_%s', $number, $class->name);
        })->values();

        // Все кружки с количеством записанных
        $activities = Activity::withCount('students')->get();

        return view('class-activities.index', compact('classes', 'activities'));
    }

    /**
     * Таблица конкретного класса — какие кружки посещает каждый ученик
     */
    public function show(Request $request, SchoolClass $schoolClass)
    {
        $students = Student::where('class', $schoolClass->id)
            ->with('activities')
            ->orderBy('surname')
            ->orderBy('name')
            ->get();

        $activities = Activity::orderBy('name')->get();

        // Карта: [student_id][activity_id] = true
        $enrollmentMap = [];
        foreach ($students as $student) {
            foreach ($student->activities as $activity) {
                $enrollmentMap[$student->id][$activity->id] = true;
            }
        }

        return view('class-activities.show', compact(
            'schoolClass',
            'students',
            'activities',
            'enrollmentMap'
        ));
    }

    /**
     * AJAX: переключение записи ученика на кружок
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'activity_id' => 'required|exists:activities,id',
        ]);

        $existing = StudentActivity::where('student_id', $validated['student_id'])
            ->where('activity_id', $validated['activity_id'])
            ->first();

        if ($existing && $existing->isActive()) {
            $existing->update(['withdrawn_at' => now()]);
            return response()->json(['enrolled' => false]);
        } elseif ($existing) {
            $existing->update(['withdrawn_at' => null, 'enrolled_at' => now()]);
            return response()->json(['enrolled' => true]);
        } else {
            StudentActivity::create([
                'student_id'  => $validated['student_id'],
                'activity_id' => $validated['activity_id'],
                'enrolled_at' => now(),
            ]);
            return response()->json(['enrolled' => true]);
        }
    }
}
