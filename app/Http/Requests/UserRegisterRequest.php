<?php

namespace App\Http\Requests;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [Rule::unique(User::class, 'email')],
            'snils' => [Rule::unique(User::class, 'snils')],
            'inn' => [Rule::unique(User::class, 'inn')],
            'passport_data' => [Rule::unique(User::class, 'passport_data')],
        ];
    }

    public function messages()
    {
        return [
            'inn.unique' => 'Такой ИНН уже существует',
            'snils.unique' => 'Такой СНИЛС уже существует',
            'passport_data.unique' => 'Такой паспорт уже существует',
        ];
    }
}
