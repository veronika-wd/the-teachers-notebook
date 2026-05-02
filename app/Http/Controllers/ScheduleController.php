<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $primarySchoolQuery = Schedule::query()->where('class', '<=', 4)->orderBy('class');
        $highSchoolQuery = Schedule::query()->where('class', '>', 4)->orderBy('class');

        $now = Carbon::now()->dayOfWeek() < 6 ? Carbon::now()->dayOfWeek() : 5;

        $day = $request->filled('day') ? $request->day : $now;
        $primarySchoolQuery->where('day', $day);
        $highSchoolQuery->where('day', $day);
        $primarySchoolAll = $primarySchoolQuery->get();
        $highSchoolAll = $highSchoolQuery->get();

        $primarySchool = $primarySchoolAll->groupBy('number');
        $highSchool = $highSchoolAll->groupBy('number');

        return view('schedule', [
            'primarySchool' => $primarySchool,
            'highSchool' => $highSchool,
            'day' => $day,
        ]);
    }

    public function edit()
    {
        $users = User::all();
        $subjects = Subject::all();
        $classes = SchoolClass::all();
        $changes = Change::all();

        return view('schedules.index', [
            'users' => $users,
            'subjects' => $subjects,
            'classes' => $classes,
            'changes' => $changes,
        ]);
    }

    public function store(Request $request)
    {
        Change::create([
            'school_class_id' => $request->class,
            'subject_id' => $request->subject,
            'user_id' => $request->user,
            'cabinet' => $request->cabinet,
            'number' => $request->number,
            'date' => $request->date,
        ]);

        return redirect()->route('schedule.edit');
    }

    public function showReplaceForm()
    {
        $classes = SchoolClass::all()->sortBy(function($class) {
            // Извлекаем число из начала строки (например, 10 из "10А")
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->get();

        $days = [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
        ];
        $maxLessons = 7;

        // 🔥 ЗАГРУЖАЕМ ТЕКУЩЕЕ РАСПИСАНИЕ
        $currentSchedule = $this->loadCurrentSchedule($classes, $days, $maxLessons);

        return view('schedules.create', compact(
            'classes', 'subjects', 'teachers', 'days', 'maxLessons', 'currentSchedule'
        ));
    }

    private function loadCurrentSchedule($classes, $days, $maxLessons)
    {
        $schedule = [];

        foreach ($classes as $class) {
            $schedule[$class->id] = [
                'class_name' => $class->name,
                'lessons' => []
            ];

            for ($lesson = 0; $lesson < $maxLessons; $lesson++) {
                $schedule[$class->id]['lessons'][$lesson] = [];

                foreach ($days as $dayNum => $dayName) {
                    $schedule[$class->id]['lessons'][$lesson][$dayNum] = [
                        'subject_id' => null,
                        'user_id' => null, // 🔥 Изменил с teacher_id на user_id
                        'cabinet' => null,
                    ];
                }
            }
        }

        $dbSchedules = Schedule::all();
        $subjectIds = Subject::pluck('id', 'name')->toArray();
        $classIds = SchoolClass::pluck('id', 'name')->toArray();

        foreach ($dbSchedules as $item) {
            $classId = $classIds[$item->class] ?? null;
            if (!$classId) continue;

            $lessonIndex = $item->number - 1;
            $dayNum = $item->day;

            if (isset($schedule[$classId]['lessons'][$lessonIndex][$dayNum])) {
                $schedule[$classId]['lessons'][$lessonIndex][$dayNum] = [
                    'subject_id' => $subjectIds[$item->subject] ?? null,
                    'user_id' => $item->user_id ? (int) $item->user_id : null, // 🔥 user_id вместо teacher_id
                    'cabinet' => $item->cabinet ?: null,
                ];
            }
        }

        return $schedule;
    }

    public function replace(Request $request)
    {
        $inputSchedule = $request->input('schedule');
        $action = $request->input('action');

        if (!$inputSchedule || !is_array($inputSchedule)) {
            return back()->withErrors(['error' => 'Нет данных расписания для сохранения.']);
        }

        $subjectNames = Subject::pluck('name', 'id')->toArray();
        $classNames = SchoolClass::pluck('name', 'id')->toArray();

        DB::beginTransaction();
        try {
            if ($action === 'full_replace') {
                $deletedCount = Schedule::query()->delete();
                Log::info("ПОЛНАЯ ЗАМЕНА: Удалено старых записей: {$deletedCount}");
                $this->insertSchedule($inputSchedule, $subjectNames, $classNames);
                $message = "✅ Расписание полностью заменено!";
            } else {
                $updatedCount = $this->upsertSchedule($inputSchedule, $subjectNames, $classNames);
                $message = "✅ Изменения сохранены! Обновлено/добавлено: {$updatedCount} записей";
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Ошибка сохранения расписания: " . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Ошибка: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function insertSchedule($inputSchedule, $subjectNames, $classNames)
    {
        $dataToInsert = [];

        foreach ($inputSchedule as $classId => $lessonsData) {
            if (!isset($classNames[$classId])) continue;

            foreach ($lessonsData as $lessonIndex => $daysData) {
                foreach ($daysData as $dayId => $lesson) {
                    if (empty($lesson['subject_id'])) continue;
                    if (!isset($subjectNames[$lesson['subject_id']])) continue;

                    $dataToInsert[] = [
                        'subject'    => $subjectNames[$lesson['subject_id']],
                        'day'        => (int) $dayId,
                        'number'     => (int) $lessonIndex + 1,
                        'class'      => $classNames[$classId],
                        'cabinet'    => isset($lesson['cabinet']) ? (int) $lesson['cabinet'] : 0,
                        'user_id'    => isset($lesson['user_id']) ? (int) $lesson['user_id'] : null, // 🔥 Только user_id
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($dataToInsert)) {
            Schedule::insert($dataToInsert);
        }
    }

    private function upsertSchedule($inputSchedule, $subjectNames, $classNames)
    {
        $dataToUpsert = [];
        $count = 0;

        foreach ($inputSchedule as $classId => $lessonsData) {
            if (!isset($classNames[$classId])) continue;

            foreach ($lessonsData as $lessonIndex => $daysData) {
                foreach ($daysData as $dayId => $lesson) {
                    if (empty($lesson['subject_id']) && empty($lesson['cabinet']) && empty($lesson['user_id'])) {
                        continue;
                    }

                    $dataToUpsert[] = [
                        'subject'    => isset($lesson['subject_id']) && isset($subjectNames[$lesson['subject_id']])
                            ? $subjectNames[$lesson['subject_id']] : '',
                        'day'        => (int) $dayId,
                        'number'     => (int) $lessonIndex + 1,
                        'class'      => $classNames[$classId],
                        'cabinet'    => isset($lesson['cabinet']) ? (int) $lesson['cabinet'] : 0,
                        'user_id'    => isset($lesson['user_id']) ? (int) $lesson['user_id'] : null, // 🔥 Только user_id
                        'updated_at' => now(),
                    ];
                    $count++;
                }
            }
        }

        if (!empty($dataToUpsert)) {
            Schedule::upsert(
                $dataToUpsert,
                ['class', 'day', 'number'],
                ['subject', 'cabinet', 'user_id', 'updated_at'] // 🔥 user_id вместо teacher_id
            );
        }

        return $count;
    }

    public function clear(Request $request)
    {
        DB::beginTransaction();
        try {
            $count = Schedule::query()->delete();
            DB::commit();

            return redirect()->back()->with('success', "🗑️ Расписание полностью очищено! Удалено записей: {$count}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при очистке: ' . $e->getMessage()]);
        }
    }
}
