<?php

namespace App\Services;

use App\Models\Book;
use App\Utils\Helper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookService
{

    public function __construct(protected Book $book)
    {
        
    }

    public function index($request)
    {
      
        $isArchive = Helper::toBoolean($request->isArchive);

        $books = $this->book->query();

        if ($isArchive) {
            $books = $books->onlyTrashed();
        }

        if ($request->search) {
            return $books = $books->search($request->search)->orderBy('title')->paginate(10);
        }

        return $books = $books->orderBy('title')->paginate(10);
    }

    public function store($request)
    {
        return $this->book::create($request->except(['author', 'department']));
    }

    public function show($id)
    {
        return $this->book::findOrFail($id);
    }

    public function destroy($id) 
    {
        $book = $this->show($id);

        return $book->delete();
    }

    public function update($request, $id)
    {
        $book = $this->show($id);
        
        $book->update($request->except(['author', 'department']));
        
        return $book;
    }

    public function restore($id)
    {
        try {
            $book = $this->book::withTrashed()->find($id);
            if (!$book) {
                throw new Exception('book not found', 404);
            } 
    
           $book->restore();

           return response()->json([
            'status'  => 200,
            'message' => 'Book Successfully Retrieved'
        ], 200);

        } catch(ModelNotFoundException $e) {
            return response()
            ->json([
                'status_code' => 404,
                'message'     => 'Unable to find requested data'
            ], 404);
        }
    }

    public function archive($request, $id) 
    {
        $book = $this->show($id);
        
        $book->update([
            'reason' => $request->reason
        ]);

        return $book->delete();
    }
}