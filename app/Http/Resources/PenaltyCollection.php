<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PenaltyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map( fn($fine) =>
            [
                'id'     => $fine->id,
                'fine'   => $fine->fine,
                'role'   => $fine->role->name
            ]
        )->all();
            
    }
}
