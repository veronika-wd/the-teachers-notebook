<?php

namespace Database\Seeders;

use App\Models\Rank;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'email' => 'admin@mail.ru',
            'password' => Hash::make('admin'),
            'name' => 'Иванов Иван Иванович',
            'snils' => '123-456-789 01',
            'inn' => '123456789012',
            'passport_data' => '4512 345678',
            'birth_date' => '1985-05-15',
            'post' => 'Учитель математики',
            'phone' => '+7 (999) 123-45-67',
            'education' => 'Московский государственный университет, математический факультет, 2007 г.',
            'experience' => 15,
            'qualification' => 'Высшая категория',
            'address' => 'г. Москва, ул. Ленина, д. 10, кв. 25',
            'role' => 'secretary',
        ]);

        User::create([
            'email' => 'user@mail.ru',
            'password' => Hash::make('user'),
            'name' => 'Андреев Андрей Андреевич',
            'snils' => '123-456-789 23',
            'inn' => '123456789023',
            'passport_data' => '2312 345678',
            'birth_date' => '1985-03-15',
            'post' => 'Учитель математики',
            'phone' => '+7 (999) 123-45-23',
            'education' => 'Московский государственный университет, математический факультет, 2007 г.',
            'experience' => 15,
            'qualification' => 'Высшая категория',
            'address' => 'г. Москва, ул. Ленина, д. 10, кв. 25',
            'role' => 'teacher',
        ]);

         User::factory(10)->create();

         $subjects = [
             'Математика', 'Русский язык', 'Литература', 'Физика',
             'Химия', 'Биология', 'История', 'География',
             'Английский язык', 'Информатика', 'Физкультура', 'ОБЖ', ' - '
         ];

         foreach ($subjects as $subject) {
             Subject::create([
                 'name' => $subject,
             ]);
         }

         for ($i = 1; $i <= 11; $i++) {
             SchoolClass::create([
                'name' => $i,
             ]);
         }

         Student::factory(10)->create();

         $this->call([
             ScheduleSeeder::class,
         ]);
    }
}
