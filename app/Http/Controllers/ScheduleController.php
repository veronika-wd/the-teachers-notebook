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
        // 1. Получаем ВСЕ классы один раз
        $allClasses = SchoolClass::all();

        $sortedClasses = $allClasses->sortBy(function ($class) {
            preg_match('/^\d+/', $class->name, $matches);
            $number = (int)($matches[0] ?? 0);
            return sprintf('%02d_%s', $number, $class->name);
        })->values();

        $primaryClasses = $sortedClasses->filter(function ($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return ((int)($matches[0] ?? 0)) <= 4;
        });

        $highClasses = $sortedClasses->filter(function ($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return ((int)($matches[0] ?? 0)) > 4;
        });

        // 2. Определяем день недели
        $currentDayIso = Carbon::now()->dayOfWeekIso;

        $day = $request->filled('day')
            ? (int)$request->day
            : ($currentDayIso === 7 ? 1 : $currentDayIso);

        // 3. Загружаем расписание с подгрузкой связей subject и teacher
        $schedules = Schedule::where('day', $day)
            ->with(['subject', 'teacher'])   // ← teacher вместо user
            ->get()
            ->groupBy('class');

        return view('schedule', [
            'schedules'      => $schedules,
            'day'            => $day,
            'primaryClasses' => $primaryClasses,
            'highClasses'    => $highClasses,
        ]);
    }

    public function edit()
    {
        $users = User::all();
        $subjects = Subject::all();
        $classes = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();
        $changes = Change::all();

        return view('schedules.index', [
            'users'    => $users,
            'subjects' => $subjects,
            'classes'  => $classes,
            'changes'  => $changes,
        ]);
    }

    public function store(Request $request)
    {
        Change::create([
            'school_class_id' => $request->class,
            'subject_id'      => $request->subject,
            'user_id'         => $request->user,
            'cabinet'         => $request->cabinet,
            'number'          => $request->number,
            'date'            => $request->date,
        ]);

        return redirect()->route('schedule.edit');
    }

    public function showReplaceForm()
    {
        $classes = SchoolClass::all()->sortBy(function($class) {
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
                'lessons'    => []
            ];

            for ($lesson = 0; $lesson < $maxLessons; $lesson++) {
                $schedule[$class->id]['lessons'][$lesson] = [];

                foreach ($days as $dayNum => $dayName) {
                    $schedule[$class->id]['lessons'][$lesson][$dayNum] = [
                        'subject_id' => null,
                        'user_id'    => null,
                        'cabinet'    => null,
                    ];
                }
            }
        }

        // Загружаем все записи расписания
        $dbSchedules = Schedule::all();

        // ID класса по его имени: [ '1' => 1, '5 Б' => 5, ... ]
        $classIds = SchoolClass::pluck('id', 'name')->toArray();

        foreach ($dbSchedules as $item) {
            $classId = $classIds[$item->class] ?? null;
            if (!$classId) continue;

            $lessonIndex = $item->number - 1;
            $dayNum      = $item->day;

            if (isset($schedule[$classId]['lessons'][$lessonIndex][$dayNum])) {
                $schedule[$classId]['lessons'][$lessonIndex][$dayNum] = [
                    // subject_id в БД теперь хранит числовой ID предмета
                    'subject_id' => $item->subject_id ? (int) $item->subject_id : null,
                    'user_id'    => $item->user_id    ? (int) $item->user_id    : null,
                    'cabinet'    => $item->cabinet    ?: null,
                ];
            }
        }

        return $schedule;
    }

    public function replace(Request $request)
    {
        $inputSchedule = $request->input('schedule');
        $action        = $request->input('action');

        if (!$inputSchedule || !is_array($inputSchedule)) {
            return back()->withErrors(['error' => 'Нет данных расписания для сохранения.']);
        }

        // ID класса по его ID: [ 1 => '1', 2 => '5 Б', ... ]
        $classNames = SchoolClass::pluck('name', 'id')->toArray();

        DB::beginTransaction();
        try {
            if ($action === 'full_replace') {
                $deletedCount = Schedule::query()->delete();
                Log::info("ПОЛНАЯ ЗАМЕНА: Удалено старых записей: {$deletedCount}");
                $this->insertSchedule($inputSchedule, $classNames);
                $message = "✅ Расписание полностью заменено!";
            } else {
                $updatedCount = $this->upsertSchedule($inputSchedule, $classNames);
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

    /**
     * Вставляем расписание — subject_id сохраняем как ЧИСЛОВОЙ ID
     */
    private function insertSchedule($inputSchedule, $classNames)
    {
        $dataToInsert = [];

        foreach ($inputSchedule as $classId => $lessonsData) {
            if (!isset($classNames[$classId])) continue;

            foreach ($lessonsData as $lessonIndex => $daysData) {
                foreach ($daysData as $dayId => $lesson) {

                    // Пропускаем пустые ячейки
                    if (empty($lesson['subject_id'])) continue;

                    $dataToInsert[] = [
                        'subject_id' => (int) $lesson['subject_id'],   // ← числовой ID предмета
                        'day'        => (int) $dayId,
                        'number'     => (int) $lessonIndex + 1,
                        'class'      => $classNames[$classId],
                        'cabinet'    => isset($lesson['cabinet']) && $lesson['cabinet'] !== ''
                            ? (int) $lesson['cabinet'] : null,
                        'user_id'    => isset($lesson['user_id']) && $lesson['user_id'] !== ''
                            ? (int) $lesson['user_id'] : null,
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

    /**
     * Upsert расписания — subject_id сохраняем как ЧИСЛОВОЙ ID
     */
    private function upsertSchedule($inputSchedule, $classNames)
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
                        'class'      => $classNames[$classId],
                        'day'        => (int) $dayId,
                        'number'     => (int) $lessonIndex + 1,
                        'subject_id' => isset($lesson['subject_id']) && $lesson['subject_id'] !== ''
                            ? (int) $lesson['subject_id'] : null,  // ← числовой ID
                        'cabinet'    => isset($lesson['cabinet']) && $lesson['cabinet'] !== ''
                            ? (int) $lesson['cabinet'] : null,
                        'user_id'    => isset($lesson['user_id']) && $lesson['user_id'] !== ''
                            ? (int) $lesson['user_id'] : null,
                        'updated_at' => now(),
                    ];
                    $count++;
                }
            }
        }

        if (!empty($dataToUpsert)) {
            Schedule::upsert(
                $dataToUpsert,
                ['class', 'day', 'number'],           // уникальные ключи
                ['subject_id', 'cabinet', 'user_id', 'updated_at']  // обновляемые поля
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

            return redirect()->back()->with('success', "Расписание полностью очищено! Удалено записей: {$count}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при очистке: ' . $e->getMessage()]);
        }
    }
}
