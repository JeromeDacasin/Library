<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use function App\Helpers\roles;

class UserService
{
    public function __construct(protected User $user)
    {
        
    }

    public function store($request)     
    {
        $username = $this->generateUsername($request);
        $this->checkForExistingUserName($username);
        $password = $this->generatePassword();
        
        $request->merge(['username' => $username, 'password' => $password->hashedPassword]);

        $this->user::create($request->all());

        return [
            'userName' => $username,
            'password' => $password->password
        ];
    }

    public function login($request)
    {
        $user = $this->user::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['message' => 'Invalid Credentials']);
        }
        
        $oneDay = 60 * 24;

        $token = $user->createToken('token', ['*'], now()->addWeek())->plainTextToken;
        $cookie = cookie('jwt', $token, $oneDay);
        
        return [
            'token' => $token,
            'cookie' => $cookie
        ];
        
    }

    public function checkForExistingUserName($username) : bool
    {
        $username = $this->user::where('username', $username)->first();

        if ($username) {
            throw new Exception("Username already existed");
        }

        return true;
    }

    public function generateUsername($request) : string
    {
        $latestUser = $this->user::latest()->first();
        $id = $latestUser ? $latestUser->id + 1 : 1;
        $username = '';

        $roles = roles($request->role_id);

        if (stristr($roles, 'student')) {
            $username = now()->format('y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);

            return $username;
        }

        $username = $request->first_name . $request->last_name;

        return $username;
    }

    public function generatePassword() : object
    {
        $randomString = Str::random();
        $hashedPassword = Hash::make($randomString); 

        return (object) [
            'password' =>  $randomString,
            'hashedPassword' => $hashedPassword
        ];
    }

    public function index()
    {
        return $this->user::all();
    }

    public function show($id)
    {
        return $this->user::findOrFail($id);
    }

    public function update($request, $id)
    {
        $user = $this->show($id);
        
       return $user->update($request->all());
    }

    public function destroy($id)
    {
        $user = $this->show($id);
        
        return $user->delete();
    }

    public function logout($request)
    {
      return $request->user()->currentAccessToken()->delete();
    }


}