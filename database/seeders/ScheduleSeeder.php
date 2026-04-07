<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('Нет пользователей для создания расписания!');
            return;
        }

        Schedule::truncate();

        $subjects = [
            'Математика', 'Русский язык', 'Литература', 'Физика',
            'Химия', 'Биология', 'История', 'География',
            'Английский язык', 'Информатика', 'Физкультура', 'ОБЖ'
        ];

        $cabinets = range(1, 35);
        $classes = SchoolClass::all();       // Классы 1-11
        $days = range(1, 5);            // Учебные дни (Пн-Пт)
        $maxLessons = 7;                // Макс. уроков в день

        $this->command->info('Создаю расписание...');
        $created = 0;

        // Для каждого класса
        foreach ($classes as $class) {
            // Для каждого дня недели
            foreach ($days as $day) {
                // Случайное количество уроков в этот день (5-7)
                $lessonsCount = rand(5, $maxLessons);

                // Для каждого номера урока
                for ($number = 1; $number <= $lessonsCount; $number++) {
                    // Случайный предмет и учитель
                    $cabinet = $cabinets[array_rand($cabinets)];
                    $subject = $subjects[array_rand($subjects)];
                    $user = $users->random();

                    Schedule::create([
                        'subject' => $subject,
                        'day' => $day,
                        'number' => $number,
                        'class' => $class->id,
                        'cabinet' => $cabinet,
                        'user_id' => $user->id,
                    ]);

                    $created++;
                }
            }
        }

        $this->command->info("✓ Создано записей расписания: {$created}");

        // Проверка на дубликаты
        $duplicates = Schedule::selectRaw('class, day, number, COUNT(*) as count')
            ->groupBy('class', 'day', 'number')
            ->havingRaw('count > 1')
            ->count();

        if ($duplicates > 0) {
            $this->command->warn("⚠ Найдено дубликатов: {$duplicates}");
        } else {
            $this->command->info("✓ Дубликаты отсутствуют");
        }
    }
}
