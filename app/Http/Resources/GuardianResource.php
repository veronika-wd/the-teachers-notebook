<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fullName' => $this->full_name,
            'phone' => $this->phone,
            'job' => $this->job,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}
