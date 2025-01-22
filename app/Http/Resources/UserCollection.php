<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($user) => [
            'id'         => $user->id,
            'username'   => $user->username,
            'role'       => $user->role->name,
            'first_name' => $user->userInformation->first_name,
            'last_name'  => $user->userInformation->last_name,
            'birth_date' => $user->userInformation->birth_date,
            'gender'     => $user->userInformation->gender,
            'email'     => $user->userInformation->email,
            'contact_number' => $user->userInformation->contact_number,
            'student_number' => $user->userInformation->student_number,
        ])->all();
    }
}
