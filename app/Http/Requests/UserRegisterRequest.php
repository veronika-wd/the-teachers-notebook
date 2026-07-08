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
            'snils_user' => [Rule::unique(User::class, 'snils')],
            'inn_user' => [Rule::unique(User::class, 'inn')],
            'passport_data_user' => [Rule::unique(User::class, 'passport_data')],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Такая почта уже используется',
            'inn_user.unique' => 'Такой ИНН уже существует',
            'snils_user.unique' => 'Такой СНИЛС уже существует',
            'passport_data_user.unique' => 'Такой паспорт уже существует',
        ];
    }
}
