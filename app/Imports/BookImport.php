<?php

namespace App\Imports;

use App\Models\Book;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BookImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Book::create([
                'title'           => $row['title'],
                'edition'         => $row['edition'],
                'price'	          => $row['price'],
                'total_quantity'  => $row['total_quantity'],
                'remaining'       => $row['remaining'],
                'acquired_via'    => $row['acquired_via'],
                'is_active'       => $row['is_active'],
                'department_id'   => $row['department_id'],
                'author_id'	      => $row['author_id'],
                'publisher_id'	  => $row['publisher_id'],
                'acquisition_id'  => $row['acquisition_id']

            ]);
        }
    }
}
