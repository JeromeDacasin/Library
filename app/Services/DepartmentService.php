<?php

namespace App\Services;

use App\Models\Department;

class DepartmentService
{
    public function __construct(protected Department $department)
    {
        
    }

    public function index($request)
    {
        if ($request->search) {
            return $this->department::where('name', 'LIKE', '%' .$request->search .'%')->paginate(10);
        }

        $departments = $this->department::orderBy('name');

        return $request->paginate ? $departments->paginate(10) : $departments->get();   
    }

    public function store($request)
    {
        return $this->department::create($request->all());
    }

    public function show($id)
    {
        return $this->department::findOrFail($id);
    }

    public function destroy($id) 
    {
        $department = $this->show($id);

        return $department->delete();
    }

    public function update($request, $id)
    {
        $department = $this->show($id);
        
        $department->update($request->all());
        
        return $department;
    }
}