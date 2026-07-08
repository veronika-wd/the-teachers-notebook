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
            'snils_guardian' => ['nullable', Rule::unique(Guardian::class, 'snils')],
            'inn_guardian' => ['nullable', Rule::unique(Guardian::class, 'inn')],
            'passport_data_guardian' => ['nullable', Rule::unique(Guardian::class, 'passport_data')],
        ];
    }

    public function messages()
    {
        return [
            'inn_guardian.unique' => 'Такой ИНН уже существует',
            'snils_guardian.unique' => 'Такой СНИЛС уже существует',
            'passport_data_guardian.unique' => 'Такой паспорт уже существует',
        ];
    }
}
