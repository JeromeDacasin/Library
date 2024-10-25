<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function __construct(protected Book $book)
    {
        
    }

    public function index()
    {
        return $this->book::paginate(10);
    }

    public function store($request)
    {
        return $this->book::create($request->all());
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
        
        $book->update($request->all());
        
        return $book;
    }
}