<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::query()->whereDate('date_start', Carbon::now())->get();

        // 1. День недели
        $currentDayOfWeek = Carbon::now()->dayOfWeek;
        $day = ($currentDayOfWeek == 0 || $currentDayOfWeek == 6) ? 5 : $currentDayOfWeek;

        // 2. Получаем все классы
        $classes = SchoolClass::all()->sortBy(function ($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return sprintf('%02d_%s', (int)($matches[0] ?? 0), $class->name);
        })->values();

        // 3. Изменения за сегодня
        $todayDate = Carbon::now()->format('Y-m-d');
        $changes = Change::query()
            ->where('date', $todayDate)
            ->with(['subject', 'teacher'])
            ->get();

        // 4. Индекс изменений для быстрого поиска
        $changesMap = [];
        foreach ($changes as $change) {
            // school_class_id - это ID класса из таблицы school_classes
            $key = $change->school_class_id . '_' . $change->number;
            $changesMap[$key] = $change;
        }

        // 5. Загружаем расписание как в рабочем контроллере
        $schedules = Schedule::where('day', $day)
            ->with(['subject', 'teacher'])
            ->get()
            ->groupBy('class');

        // 6. Создаем маппинг: ID класса -> название класса
        $classIdToName = SchoolClass::pluck('name', 'id')->toArray();

        // 7. Формируем расписание ПО КЛАССАМ с учетом замен
        $schedule = [];

        foreach ($classes as $class) {
            $classSchedule = [];

            // Получаем уроки для этого класса по названию
            $classLessons = $schedules[$class->name] ?? collect();

            for ($i = 1; $i <= 7; $i++) {
                $lesson = $classLessons->firstWhere('number', $i);

                if ($lesson) {
                    // Инициализируем флаги
                    $lesson->is_replacement = false;
                    $lesson->replacementTeacherName = null;
                    $lesson->replacementUserId = null;
                    $lesson->originalUserId = $lesson->user_id;

                    // Проверяем замены по school_class_id и номеру урока
                    $searchKey = $class->id . '_' . $i;

                    if (isset($changesMap[$searchKey])) {
                        $change = $changesMap[$searchKey];

                        // Применяем данные замены
                        if ($change->subject) {
                            $lesson->subject = $change->subject;
                        }
                        if ($change->cabinet) {
                            $lesson->cabinet = $change->cabinet;
                        }

                        // Учитель замены
                        if ($change->teacher) {
                            $lesson->replacementTeacherName = $change->teacher->name;
                            $lesson->replacementUserId = $change->teacher->id;
                        }

                        $lesson->is_replacement = true;
                    }

                    $classSchedule[$i] = $lesson;
                } else {
                    $classSchedule[$i] = null;
                }
            }

            $schedule[$class->id] = $classSchedule;
        }

        $notifications = Notification::query()->orderByDesc('created_at')->limit(2)->get();

        return view('home', [
            'events' => $events,
            'shedule' => $schedule,
            'notifications' => $notifications,
            'classes' => $classes,
        ]);
    }

    public function calendar()
    {
        return view('calendar');
    }
}
