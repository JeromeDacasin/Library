<?php

namespace App\Services;

use App\Models\BorrowedBook;
use App\Models\Penalty;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;

class BorrowedBookService 
{
    public function __construct(protected BorrowedBook $borrowedBook, protected User $user, protected Penalty $penalty)
    {
        
    }


    public function store($request) 
    {
        $this->borrowedBook::create($request->all());
    }

    public function update($request, $id)
    {

        $borrowedBooks = $this->show($id);

        if (stristr($request->status, 'borrowed')) {
            $request = $this->borrowBook($request);
          }

        if (stristr($request->status, 'returned')) {
            $request = $this->returnBook($request, $borrowedBooks);
        }
        
        return $borrowedBooks->update($request->all());
    }


    public function show($id) 
    {
        return $this->borrowedBook::findOrFail($id);
    }

    public function index($request)
    {
        if ($request->status) {
            return $this->borrowedBook::where('status', $request->status)->paginate(10);
        }
        return $this->borrowedBook::paginate(10);
    }

    public function delete($id)
    {
        $borrowedBook = $this->show($id);

        return $borrowedBook->delete();
    }

    private function calculateWeekendsDate()
    {
        $daysToMustReturn = 7;
        $now = now();

        $addedDays = 0;

        while ($addedDays < $daysToMustReturn) {
            $now->addDay();

            if (!$now->isWeekend()) {
                $addedDays++;
            }

        }

        return $now->format('Y-m-d');  
    }

    private function borrowBook($request)
    {
        $dateWithoutWeekends = $this->calculateWeekendsDate();

        $request->merge([
            'must_return_date' => $dateWithoutWeekends, 
            'borrowed_date' => now()
        ]);
        
        return $request;
        
    }

    private function returnBook($request, $book)
    {

        if(!stristr($book, 'borrowed')) {
            throw new Exception('Books is not Borrowed right now', 409);
        }

        $user = $this->user::find($book->user_id);

        if (!$user) {
            throw new Exception('No User Found', 404);
        }
 
        $penalty = $this->penalty->where('role_id',$user->role_id)->first();

        
        if (!$penalty) {
            throw new Exception('No fines for this roles', 404);
        }
 
        $now = now();
        $formatToday = $now->format('Y-m-d');
        $period = 0;

        if ($now > $book->must_return_date) {
            $formattedDateMustReturn = Carbon::parse($book->must_return_date)->addDay()->format('Y-m-d');

            CarbonPeriod::macro('countWeekdays', function () {
                return $this->filter('isWeekday')->count();
            });

            $period = CarbonPeriod::create($formattedDateMustReturn, $formatToday)->countWeekdays();
        }

        $request->merge([
            'returned_date' => $now, 
            'total_penalty' => $period * $penalty->fine

        ]);

        return $request;
        
    }

    
}