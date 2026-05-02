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
            'name' => 'Admin',
            'snils' => 0,
            'inn' => 0,
            'passport_data' => 0,
            'birth_date' => '2026-05-1',
            'post' => 'admin',
            'phone' => '89997776666',
            'education' => 'none',
            'qualification' => 'none',
            'address' => 'none',
            'experience' => 0,
            'role' => 'admin',
        ]);

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
    }
}
