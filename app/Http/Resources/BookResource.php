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
            'title'  => $this->title,
            'price'  => $this->price,
            'edition'          => $this->edition,
            'total_quantity'   => $this->total_quantity,
            'remaining'        => $this->remaining,
            'acquired_via'     => $this->acquired_via,
            'acquisition_id'   => $this->acquisition_id,
            'is_active'        => $this->is_active,
            'author_id'        => $this->author_id,
            'department_id'    => $this->department_id
        ];
    }
}
