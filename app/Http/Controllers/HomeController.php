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

        // 2. Изменения за сегодня с учителем
        $todayDate = Carbon::now()->format('Y-m-d');
        $changes = Change::query()
            ->where('date', $todayDate)
            ->with(['subject', 'teacher']) // Подгружаем и предмет, и учителя замены
            ->get();

        // 3. Индекс изменений
        $changesMap = [];
        foreach ($changes as $change) {
            $key = $change->school_class_id . '_' . $change->number;
            $changesMap[$key] = $change;
        }

        $schedule = []; // Обратите внимание на переменную $shedule, как у вас в шаблоне

        for ($i = 1; $i <= 7; $i++) {
            $lessons = Schedule::query()
                ->with('teacher')
                ->where('day', $day)
                ->where('number', $i)
                ->orderBy('class')
                ->get();

            foreach ($lessons as $lesson) {
                $searchKey = $lesson->class . '_' . $lesson->number;

                // Инициализируем флаги
                $lesson->is_replacement = false;
                $lesson->replacementTeacherName = null;
                $lesson->originalUserId = $lesson->user_id; // Сохраняем оригинальный ID для проверки "мой урок"

                if (isset($changesMap[$searchKey])) {
                    $change = $changesMap[$searchKey];

                    // Применяем данные замены
                    if ($change->subject) {
                        $lesson->subject = $change->subject->name;
                    }
                    if ($change->cabinet) {
                        $lesson->cabinet = $change->cabinet;
                    }

                    // Самое важное: учитель
                    if ($change->teacher) {
                        $lesson->replacementTeacherName = $change->teacher->name; // Или name, full_name - зависит от вашей модели User
                        // Для подсветки "своего" урока при замене, нужно сравнить ID заменяющего учителя с auth()->id()
                        $lesson->replacementUserId = $change->teacher->id;
                    }

                    $lesson->is_replacement = true;
                }
            }

            $schedule[$i] = $lessons;
        }

        $notifications = Notification::query()->orderByDesc('created_at')->limit(2)->get();
        $classes = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();

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
