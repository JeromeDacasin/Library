<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'username'   => $this->username,
            'role'       => $this->role->name,
            'first_name' => $this->userInformation->first_name,
            'last_name'  => $this->userInformation->last_name,
            'birth_date' => $this->userInformation->birth_date,
            'gender'     => $this->userInformation->gender,
            'email'      => $this->userInformation->email,
            'contact_number' => $this->userInformation->contact_number,
            'student_number' => $this->userInformation->student_number ?? null,
            'is_active'      => $this->is_active
        ];
    }
}
