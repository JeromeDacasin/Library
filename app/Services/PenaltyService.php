<?php

namespace App\Services;

use App\Models\Penalty;


class PenaltyService 
{
    public function __construct(protected Penalty $penalty) 
    {

    }

    public function index($request)
    {
        if ($request->search) {
            return $this->penalty::where('name', 'LIKE', '%' .$request->search .'%')->paginate(10);
        }

        return $this->penalty::paginate(10);
    }

    public function store($request)
    {
        return $this->penalty::create($request->all());
    }

    public function show($id)
    {
        return $this->penalty::findOrFail($id);
    }

    public function destroy($id) 
    {
        $penalty = $this->show($id);

        return $penalty->delete();
    }

    public function update($request, $id)
    {
        $penalty = $this->show($id);
        
        $penalty->update($request->all());
        
        return $penalty;
    }
}