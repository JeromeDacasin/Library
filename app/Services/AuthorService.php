<?php 

namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function __construct(protected Author $author)
    {
        
    }

    public function index($request)
    {
        return $request->paginate ? $this->author::paginate(10) : $this->author->get();   
    }

    public function store($request)
    {
        return $this->author::create($request->all());
    }

    public function show($id)
    {
        return $this->author::findOrFail($id);
    }

    public function destroy($id) 
    {
        $author = $this->show($id);

        return $author->delete();
    }

    public function update($request, $id)
    {
        $author = $this->show($id);
        
        $author->update($request->all());
        
        return $author;
    }
}