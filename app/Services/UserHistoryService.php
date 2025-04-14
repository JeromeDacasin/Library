<?php

namespace App\Services;

use App\Http\Resources\BorrowedBookCollection;
use App\Models\BorrowedBook;
use Illuminate\Support\Facades\DB;

class UserHistoryService 
{
    public function __construct(protected BorrowedBook $borrowedBook)
    {
        
    }

    public function getHistoryUser($id)
    {
        $histories = $this->borrowedBook::where('user_id', $id)->paginate(10);
        

        return new BorrowedBookCollection($histories);
    } 
}