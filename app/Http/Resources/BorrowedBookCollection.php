<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function App\Helpers\formatNullableDate;

class BorrowedBookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function($borrowedBook) {
            return [
                'id'     => $borrowedBook->id,
                'user'   => $borrowedBook->user->userInformation->first_name . ' ' . $borrowedBook->user->userInformation->last_name,
                'book'   => $borrowedBook->book->title,
                'student_id' => $borrowedBook->user->userInformation->student_number,
                'personnel' => $borrowedBook->user->role->name,     
                'status' => $borrowedBook->status,
                'request_date' => $borrowedBook->request_date->format('Y-m-d'),
                'borrowed_date' => formatNullableDate($borrowedBook->borrowed_date),
                'must_return_date' => formatNullableDate($borrowedBook->must_return_date),
                'returned_date' => formatNullableDate($borrowedBook->returned_date),
                'total_penalty' => $borrowedBook->total_penalty
            ];
        })->all();
    }
}
