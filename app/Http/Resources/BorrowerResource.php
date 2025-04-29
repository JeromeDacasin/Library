<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_borrow' => $this->total,
            'fullname' =>  $this->user->userInformation->first_name . ' ' . $this->user->userInformation->last_name,
            'total_penalty' => $this->total_penalty
        ];
    }
}
