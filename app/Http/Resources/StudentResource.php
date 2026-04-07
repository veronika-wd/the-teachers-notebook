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
            'birthDate' => $this->birth_date,
            'class' => $this->class,
            'passportData' => $this->passport_data,
            'parent' => $this->parent_full_name,
            'parentPhone' => $this->parent_phone,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}
