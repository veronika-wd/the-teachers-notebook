<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\Theme;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // Создаём кружки с разным расписанием
        $activities = [
            [
                'name'      => 'Шахматы',
                'week_days' => [1, 3, 5], // Пн, Ср, Пт — 3 раза
                'room'      => '205',
                'start_time'=> '14:00',
                'end_time'  => '15:30',
            ],
            [
                'name'      => 'Рисование',
                'week_days' => [2, 4], // Вт, Чт — 2 раза
                'room'      => '112',
                'start_time'=> '15:00',
                'end_time'  => '16:30',
            ],
            [
                'name'      => 'Футбол',
                'week_days' => [6], // Сб — 1 раз
                'room'      => 'Спортзал',
                'start_time'=> '10:00',
                'end_time'  => '12:00',
            ],
            [
                'name'      => 'Программирование',
                'week_days' => [1, 4], // Пн, Чт — 2 раза
                'room'      => '301',
                'start_time'=> '15:30',
                'end_time'  => '17:00',
            ],
            [
                'name'      => 'Хор',
                'week_days' => [3], // Ср — 1 раз
                'room'      => 'Актовый зал',
                'start_time'=> '14:30',
                'end_time'  => '16:00',
            ],
        ];

        foreach ($activities as $data) {
            $activity = Activity::create($data);

            // Записываем случайных учеников
            $students = Student::inRandomOrder()->take(rand(5, 15))->get();

            foreach ($students as $student) {
                $sa = StudentActivity::create([
                    'student_id'  => $student->id,
                    'activity_id' => $activity->id,
                    'enrolled_at' => now()->subMonths(rand(1, 6)),
                ]);

                // Генерируем посещаемость за текущий месяц
                $meetingDates = $activity->getMeetingDatesForMonth(now()->year, now()->month);

                foreach ($meetingDates as $date) {
                    if ($date->isPast() || $date->isToday()) {
                        \App\Models\ActivityAttendance::create([
                            'student_activity_id' => $sa->id,
                            'date'   => $date,
                            'status' => rand(1, 10) > 2 ? 'present' : 'absent',
                        ]);
                    }
                }

                // Генерируем темы
                $pastDates = $activity->getMeetingDatesForMonth(
                    now()->subMonth()->year,
                    now()->subMonth()->month
                );

                foreach (array_slice($pastDates, 0, 3) as $i => $date) {
                    Theme::create([
                        'activity_id' => $activity->id,
                        'name'        => "Тема занятия " . ($i + 1),
                        'date'        => $date,
                        'description' => "Описание темы занятия",
                    ]);
                }
            }
        }
    }
}
