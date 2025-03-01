<?php

namespace App\Services;

use App\Models\Book;
use App\Utils\Helper;

class BookService
{

    public function __construct(protected Book $book)
    {
        
    }

    public function index($request)
    {
      
        $isArchive = Helper::toBoolean($request->isArchive);

        $books = $this->book;

        if ($isArchive) {
            $books = $books->onlyTrashed();
        }

        if ($request->search) {
            return $books = $books->where('title', 'LIKE', '%' .$request->search .'%')->paginate();
        }

        return $books = $books->paginate(10);
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
}