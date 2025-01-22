<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function __construct(protected Book $book)
    {
        
    }

    public function index($request)
    {
        if ($request->search) {
            return $this->book::where('name', 'LIKE', '%' .$request->search .'%')->paginate();
        }

        return $this->book::paginate(10);
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