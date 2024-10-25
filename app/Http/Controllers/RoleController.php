<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    public function __construct(protected RoleService $roleApi)
    {
    
    }

    public function store(RoleCreateRequest $request)
    {
        try {
            
            $data = $this->roleApi->store($request);

            return new RoleResource($data);

        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'status'  => $e->getCode()
            ]);

        }
    }

    public function index()
    {
        $roles = $this->roleApi->index();

        return new RoleCollection($roles);
    }

    public function show($id)
    {
        try {
            $role = $this->roleApi->show($id);

            return response()->json([
                'data' => new RoleResource($role),
                'status' => 200,
                'message' => 'Successfully Update'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Role not found'
            ], 404);
        }
       

        return new RoleResource($role);
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        try {
            $role = $this->roleApi->update($request, $id);

            return response()->json([
                'data' => new RoleResource($role),
                'status' => 200,
                'message' => 'Successfully Update'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }
 
    }

    public function destroy($id)
    {
        try {

            $data = $this->roleApi->destroy($id);

            return response()->json([
                'data'    => $data,
                'status'  => 200,
                'message' => 'Successful Delete'
            ]);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Role not found'
            ], 404);
        } 
        
    }

}
