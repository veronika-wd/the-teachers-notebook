<?php

namespace App\Http\Requests;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardianRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'snils' => [Rule::unique(Guardian::class, 'snils')],
            'inn' => [Rule::unique(Guardian::class, 'inn')],
            'passport_data' => [Rule::unique(Guardian::class, 'passport_data')],
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
