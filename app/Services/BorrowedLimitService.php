<?php

namespace App\Services;

use App\Models\BorrowedLimit;

class BorrowedLimitService 
{
    public function __construct(protected BorrowedLimit $borrowedLimit)
    {
        
    }

    public function store($request) 
    {
        return $this->borrowedLimit::create($request->except(['role']));
    }

    public function index($request)
    {
        if ($request->search) {
            return $this->borrowedLimit::where('name', 'LIKE', '%' .$request->search .'%')->paginate(10);
        }

        return $request->paginate ? $this->borrowedLimit::paginate(10) : $this->borrowedLimit->get();   
    }

    public function show($id)
    {
        return $this->borrowedLimit::findOrFail($id);
    }

    public function update($request, $id)
    {
        $borrowedLimit = $this->show($id);
      
        $borrowedLimit->update($request->except(['role']));

        return $borrowedLimit;
    }

    public function destroy($id)
    {
        $borrowedLimit = $this->show($id);

        return $borrowedLimit->delete();
 
    }
}