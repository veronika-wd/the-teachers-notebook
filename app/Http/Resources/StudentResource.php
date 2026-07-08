<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Student */
class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'birthDate' => date_format($this->birth_date, 'd.m.Y'),
            'class' => $this->schoolClass->name,
            'passportData' => $this->passport_data,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}
