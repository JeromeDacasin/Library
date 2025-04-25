<?php

namespace App\Services;

use App\Exports\BookBorrowedExport;
use App\Http\Resources\BorrowedBookCollection;
use App\Http\Resources\BorrowedBookResource;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\BorrowedLimit;
use App\Models\Penalty;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
        $id = Auth::user()->id;
        $now = now();

        $getStatusOfUser = $this->borrowedBook::where('status', 'borrowed')
            ->where('user_id', $id)
            ->where('must_return_date', '<', $now)->first();

        $this->checkAllowableBookPerRole($id);

        if ($getStatusOfUser) {
            $message = 'You have an unpaid fine. Please settle your balance to continue borrowing books.';
            throw new Exception($message, 400);
        }
        
        $book = $this->book::find($request->book_id);

        $this->checkBook($book);

       
        
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

        if ($request->export) {
            $bookExport = $borrowedBooks->get();
            $bookExports = new BorrowedBookCollection($bookExport);
            return Excel::download(new BookBorrowedExport($bookExports->toArray(request())), 'History.csv');
        }

        if ($request->search) {
            $borrowedBooks = $borrowBooks->search($search);
        }

        return $borrowedBooks->orderBy('request_date', 'desc')->paginate(10);
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

    private function borrowBook($request, $borrowBooks)
    {
        $dateWithoutWeekends = $this->calculateWeekendsDate();

        $request->merge([
            'must_return_date' => $dateWithoutWeekends, 
            'borrowed_date' => now()
        ]);

        $book = $this->book::find($borrowBooks->book_id);
   
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
        dd('dsadsa');
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
                'bb.request_date',
                'bb.reason',
                'bb.total_penalty'
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

    private function checkAllowableBookPerRole($id)
    {
        $role = Auth::user()->role_id;
    
        $limitPerRole = BorrowedLimit::where('role_id', $role)
            ->value('number');
        
        $borrowed = $this->borrowedBook::whereIn('status', ['requested', 'borrowed'])->where('user_id', $id)->count();
        
        if ($borrowed >= $limitPerRole) {
            throw new Exception('It seems you have borrowed / requested the maximum number of books allowed.');
        } 
    }

    
}