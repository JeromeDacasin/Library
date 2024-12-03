<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'price'  => $this->price,
            'edition'    => $this->edition,
            'quantity'   => $this->quantity,
            'status'     => $this->status,
            'author_id'  => $this->author_id,
            'department_id' => $this->department_id
        ];
    }
}
