<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ValidatePassword;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Services\UserApi;
use App\Services\UserService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct(protected UserService $userApi)
    {
        
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userApi->login($request);

        return response()->json([
            'status'  => 200,
            'data'    => $user,
            'message' => 'Success'
        ],200)->withCookie($user['cookie']);
    }

    public function store(UserCreateRequest $request)
    {
        
        DB::beginTransaction();
        try {
            $data = $this->userApi->store($request);

            DB::commit();
            return response()->json([
                'data' => $data,
                'status' => 200,
                'message' => 'successfully created'
            ], 200);
            

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'status'  => $e->getCode()
            ],$e->getCode());
        }    
    }

    public function index(Request $request)
    {

        $users = $this->userApi->index($request);

        return new UserCollection($users);
    }

    public function show($id)
    {
        try {

            $users = $this->userApi->show($id);

            return new UserResource($users);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {

            $users = $this->userApi->update($request, $id);

            return response()->json([
                'data'    => $users,
                'status'  => 200,
                'message' => 'Successful Update'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status'  => $e->getCode()
            ],$e->getCode());
        }

    }

    public function destroy($id)
    {
        try {

            $users = $this->userApi->destroy($id);

            return response()->json([
                'data'    => $users,
                'status'  => 200,
                'message' => 'Successful Delete'
            ]);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        } 
    }

    public function logout(Request $request)
    {
        $this->userApi->logout($request);

        return response()->json([
            'message' => 'Logout',
            'status'  => 200
        ]);
    }

    public function changePassword(ValidatePassword $request)
    {
        try {

            $this->userApi->updatePassword($request);

            return response()->json([
                'status'  => 200,
                'message' => 'Successful Change Password'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status'  => $e->getCode()
            ],$e->getCode());
        }
    }
 
}
