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
                'name'   => $book->name,
                'author' => $book->author->first_name . ' ' . $book->author->last_name,
                'price'  => $book->price,
                'edition'    => $book->edition,
                'quantity'   => $book->quantity,
                'department' => $book->department->name,
                'status'     => $book->status

            ]
       )->all();
          
    }
}
