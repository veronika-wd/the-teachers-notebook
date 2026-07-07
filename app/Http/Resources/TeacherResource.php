<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fullname' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'birthDate' => date_format($this->birth_date, 'd.m.Y'),
            'post' => $this->post,
            'snils' => $this->snils,
            'inn' => $this->inn,
            'passportData' => $this->passport_data,
            'phone' => $this->phone,
            'education' => $this->education,
            'experience' => $this->experience,
            'qualification' => $this->qualification,
            'address' => $this->address

        ];
    }
}
