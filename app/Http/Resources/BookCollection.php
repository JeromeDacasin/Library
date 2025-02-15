<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
       return $this->collection->map( fn($book) =>
            [
                'id'     => $book->id,
                'title'  => $book->title,
                'author' => $book->author->first_name . ' ' . $book->author->last_name,
                'price'  => $book->price,
                'edition'          => $book->edition,
                'total_quantity'   => $book->total_quantity,
                'remaining'        => $book->remaining,
                'acquired_via'     => $book->acquired_via,
                'department'       => $book->department->name,
                'is_active'        => $book->is_active

            ]
       )->all();
          
    }
}
