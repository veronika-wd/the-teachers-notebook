<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->email,
            'password' => Hash::make('1234'),
            'name' => fake()->name,
            'snils' => fake()->unique()->randomNumber(),
            'inn' => fake()->unique()->randomNumber(),
            'passport_data' => fake()->unique()->randomNumber(),
            'birth_date' => fake()->date,
            'post' => 'Учитель математики',
            'phone' => fake()->phoneNumber(),
            'education' => 'Московский государственный университет, математический факультет, 2007 г.',
            'experience' => 15,
            'qualification' => 'Высшая категория',
            'address' => 'г. Москва, ул. Ленина, д. 10, кв. 25',
            'role' => 'teacher',
        ];
    }

}
