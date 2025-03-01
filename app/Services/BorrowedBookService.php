<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\Penalty;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowedBookService 
{
    public function __construct(
        protected BorrowedBook $borrowedBook, 
        protected User $user, 
        protected Penalty $penalty,
        protected Book $book
    )
    {
        
    }


    public function store($request) 
    {
        $book = $this->book::find($request->book_id);

        $this->checkBook($book);

        $id = Auth::user()->id;
        
        $request->merge([
            'request_date' => now(),
            'user_id'      => $id
        ]);

       
        $this->borrowedBook::create($request->all());
    }

    public function update($request, $id)
    {

        $borrowedBooks = $this->show($id);

        if (stristr($request->status, 'borrowed')) {
            $request = $this->borrowBook($request, $borrowedBooks);
        }

        if (stristr($request->status, 'returned')) {
            $request = $this->returnBook($request, $borrowedBooks);
        }
        
        $borrowedBooks->update($request->all());

        return $borrowedBooks;
    }


    public function show($id) 
    {
        return $this->borrowedBook::findOrFail($id);
    }

    public function index($request)
    {

        $borrowBooks = $this->borrowedBook->query();
        $search = $request->search;
        
        if ($request->status) {
            
            if ($request->status === 'returned') {
                $borrowedBooks = $borrowBooks->where(function($query) {
                    $query->whereNot('status', 'requested')
                    ->whereNot('status', 'borrowed');
                })->bookWithTrashed(); 
            } else {
                $borrowedBooks = $borrowBooks->where('status', $request->status)->bookWithTrashed();
            }
            
        }

        if ($request->search) {
            $borrowBooks = $borrowBooks->search($search);
        }

        return $borrowedBooks->paginate(10);
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

    private function borrowBook($request, $id)
    {
        $dateWithoutWeekends = $this->calculateWeekendsDate();

        $request->merge([
            'must_return_date' => $dateWithoutWeekends, 
            'borrowed_date' => now()
        ]);

        $book = $this->book::find($request->book_id);
        
        $this->updateBook($book);
        
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

        $this->book::where('id', $book->book_id)
            ->increment('remaining');

        $request->merge([
            'returned_date' => $now, 
            'total_penalty' => $period * $penalty->fine

        ]);

        return $request;
        
    }

    public function myBooks()
    {
        $userId = Auth::user()->id;

        $histories = DB::table('borrowed_books as bb')
            ->join('books as b', 'b.id', 'bb.book_id')
            ->join('users as u', 'u.id', 'bb.user_id')
            ->where('bb.user_id', $userId)
            ->select(
                'b.title',
                'bb.status',
                'bb.must_return_date',
                'bb.returned_date',
                'bb.request_date'
            )
            ->orderBy('bb.status')
            ->get();

        return response()->json($histories);

    }

    private function checkBook($book) 
    {
    
        $zero = 0;

        if ($book->remaining === $zero || $book->is_active === $zero)
        {
            throw new Exception('Book is Not available right now', 422);
        } 

    }

    private function updateBook($book) 
    {
        $book->update([
            'remaining' => $book->remaining - 1
        ]);
    }

    
}