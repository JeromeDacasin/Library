<?php

namespace App\Services;

use App\Models\Role;

class RoleService
{
    public function __construct(protected Role $role)
    {
        
    }

    public function store($request) 
    {
        return $this->role::create($request->all());
    }

    public function index($request)
    {
        if ($request->search) {
            return $this->role::where('name', 'LIKE', '%' .$request->search .'%')->paginate(10);
        }

        return $request->paginate ? $this->role::paginate(10) : $this->role->get();   
    }

    public function show($id)
    {
        return $this->role::findOrFail($id);
    }

    public function update($request, $id)
    {
        $role = $this->show($id);
      
        $role->update($request->all());

        return $role;
    }

    public function destroy($id)
    {
        $role = $this->show($id);

        return $role->delete();
 
    }
}