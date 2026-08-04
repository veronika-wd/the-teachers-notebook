<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\ActivityAttendance;
use App\Models\Theme;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // =========================================================
    // СТРАНИЦА 1: Список всех кружков (переход к таблицам)
    // =========================================================
    public function index()
    {
        $activities = Activity::withCount('students')->get();

        return view('activities.index', compact('activities'));
    }

    // =========================================================
    // СТРАНИЦА 2: CRUD кружка — создание
    // =========================================================
    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'week_days'   => 'required|array|min:1|max:3',
            'week_days.*' => 'integer|between:1,7',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i',
            'room'        => 'nullable|string|max:100',
        ]);

        // Сортируем дни недели для консистентности
        $validated['week_days'] = collect($validated['week_days'])
            ->unique()->sort()->values()->toArray();

        Activity::create($validated);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Кружок успешно создан!');
    }

    // =========================================================
    // СТРАНИЦА 3: Таблица посещаемости кружка
    // =========================================================
    public function showAttendance(Request $request, Activity $activity)
    {
        $year  = $request->input('year',  now()->year);
        $month = $request->input('month', now()->month);

        // Получаем только даты, когда кружок проводится
        $meetingDates = $activity->getMeetingDatesForMonth($year, $month);

        // Получаем всех активных учеников кружка
        $studentActivities = StudentActivity::where('activity_id', $activity->id)
            ->whereNull('withdrawn_at')
            ->with(['student', 'attendances' => function ($q) use ($year, $month) {
                $q->whereYear('date', $year)
                    ->whereMonth('date', $month);
            }])
            ->get()
            ->sortBy('student.surname');

        // Формируем удобную структуру: [student_activity_id][date] = status
        $attendanceMap = [];
        foreach ($studentActivities as $sa) {
            foreach ($sa->attendances as $att) {
                $attendanceMap[$sa->id][$att->date->format('Y-m-d')] = $att->status;
            }
        }

        // Все кружки для навигации
        $allActivities = Activity::orderBy('name')->get();

        return view('activities.attendance', compact(
            'activity',
            'meetingDates',
            'studentActivities',
            'attendanceMap',
            'year',
            'month',
            'allActivities'
        ));
    }

    // =========================================================
    // Сохранение посещаемости
    // =========================================================
    public function saveAttendance(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|array',
            'attendance.*.*' => 'nullable|in:present,absent,late',
        ]);

        foreach ($validated['attendance'] as $studentActivityId => $dates) {
            foreach ($dates as $date => $status) {
                if (empty($status)) continue;

                ActivityAttendance::updateOrCreate(
                    [
                        'student_activity_id' => $studentActivityId,
                        'date' => $date,
                    ],
                    [
                        'status' => $status,
                    ]
                );
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Посещаемость сохранена!');
    }

    // =========================================================
    // СТРАНИЦА 4: Темы занятий
    // =========================================================
    public function showThemes(Request $request, Activity $activity)
    {
        $themes = $activity->themes()
            ->orderByDesc('date')
            ->paginate(20);

        $allActivities = Activity::orderBy('name')->get();

        return view('activities.themes', compact(
            'activity',
            'themes',
            'allActivities'
        ));
    }

    public function storeTheme(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
        ]);

        $activity->themes()->create($validated);

        return redirect()
            ->back()
            ->with('success', 'Тема добавлена!');
    }

    public function destroyTheme(Activity $activity, Theme $theme)
    {
        $theme->delete();
        return redirect()->back()->with('success', 'Тема удалена.');
    }

    // =========================================================
    // Управление записью учеников на кружок
    // =========================================================
    public function manageEnrollment(Request $request, Activity $activity)
    {
        $search = $request->input('search', '');

        $enrolledIds = $activity->students()->pluck('students.id')->toArray();

        $students = Student::when($search, function ($q) use ($search) {
            $q->where('surname', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('patronymic', 'like', "%{$search}%");
        })
            ->orderBy('surname')
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($enrolledIds) {
                $student->is_enrolled = in_array($student->id, $enrolledIds);
                return $student;
            });

        $allActivities = Activity::orderBy('name')->get();

        return view('activities.enrollment', compact(
            'activity',
            'students',
            'enrolledIds',
            'allActivities'
        ));
    }

    public function toggleEnrollment(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $existing = StudentActivity::where('student_id', $validated['student_id'])
            ->where('activity_id', $activity->id)
            ->first();

        if ($existing) {
            if ($existing->isActive()) {
                // Отчисляем
                $existing->update(['withdrawn_at' => now()]);
                $message = 'Ученик отчислен из кружка.';
            } else {
                // Повторно зачисляем
                $existing->update(['withdrawn_at' => null, 'enrolled_at' => now()]);
                $message = 'Ученик повторно зачислен в кружок.';
            }
        } else {
            // Новая запись
            StudentActivity::create([
                'student_id'  => $validated['student_id'],
                'activity_id' => $activity->id,
                'enrolled_at' => now(),
            ]);
            $message = 'Ученик зачислен в кружок.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.classes.index');
    }
}
