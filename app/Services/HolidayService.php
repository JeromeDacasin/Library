<?php

namespace App\Services;

use App\Models\Holiday;

class HolidayService 
{
    public function __construct(protected Holiday $holiday)
    {
        
    }

    public function index($request)
    {
        if ($request->search) {
            return $this->holiday::where('name', 'LIKE', '%' .$request->search .'%')->paginate(10);
        }

        return $request->paginate ? $this->holiday::paginate(10) : $this->holiday->get();   
    }

    public function store($request)
    {
        return $this->holiday::create($request->all());
    }

    public function show($id)
    {
        return $this->holiday::findOrFail($id);
    }

    public function destroy($id) 
    {
        $holiday = $this->show($id);

        return $holiday->delete();
    }

    public function update($request, $id)
    {
        $holiday = $this->show($id);
        
        $holiday->update($request->all());
        
        return $holiday;
    }
}