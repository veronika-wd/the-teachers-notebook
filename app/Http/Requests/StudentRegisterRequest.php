<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'snils_student' => ['nullable', Rule::unique(Student::class, 'snils')],
            'inn_student' => ['nullable', Rule::unique(Student::class, 'inn')],
            'passport_data_student' => ['nullable', Rule::unique(Student::class, 'passport_data')],
        ];
    }

    public function messages()
    {
        return [
            'inn_student.unique' => 'Такой ИНН уже существует',
            'snils_student.unique' => 'Такой СНИЛС уже существует',
            'passport_data_student.unique' => 'Такой паспорт уже существует',
        ];
    }
}
