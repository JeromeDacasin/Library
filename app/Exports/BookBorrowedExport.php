<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookBorrowedExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct(protected $history)
    {
        $this->history = $history;
    }

    public function collection()
    {
        return collect($this->history);   
    }

    public function headings(): array
    {
        return [
            "id",
            "user",
            "book",
            "status",
            "request_date",
            "borrowed_date",
            "must_return_date",
            "returned_date",
            "total_penalty",
        ];
    }
}