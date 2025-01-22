<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInformation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use function App\Helpers\roles;

class UserService
{
    public function __construct(protected User $user, protected UserInformation $userInformation)
    {
        
    }

    public function store($request)     
    {
        $username = $this->generateUsername($request);
        $this->checkForExistingUserName($username);
        $password = $this->generatePassword();

        $user = $this->user::create([
            'username' => $username,
            'password' => $password->hashedPassword,
            'role_id'  => $request->role_id
        ]); 


        if (!$user) {
            throw new Exception('Something went Wrong', 400);
        }
 
        $this->userInformation::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'email'      => $request->email,
            'contact_number' => $request->contact_number,
            'student_number' => $request->role_id === 2 ? ($request->student_number ?? $username) : null,
            'user_id'        => $user->id
        ]);

        return [
            'userName' => $username,
            'password' => $password->password
        ];
    }

    public function login($request)
    {
        $user = $this->user::with('role')->where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['message' => 'Invalid Credentials']);
        }
        
        $oneDay = 60 * 24;

        $token = $user->createToken('token', ['*'], now()->addWeek())->plainTextToken;
        $cookie = cookie('jwt', $token, $oneDay);
        
        return [
            'token' => $token,
            'cookie' => $cookie,
            'role'   => $user->role->name
            
        ];
        
    }

    public function checkForExistingUserName($username) : bool
    {
        $username = $this->user::where('username', $username)->first();

        if ($username) {
            throw new Exception("Username already existed", 409);
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

    public function index($request)
    {
        return $this->user::where('role_id', $request->role_id)->paginate(10);
    }

    public function show($id)
    {
        return $this->user::findOrFail($id);
    }

    public function update($request, $id)
    {

        $user = $this->show($id);

        $data = $request->except(['username','role','is_active','role_id']);

        return $this->userInformation::where('user_id', $user->id)
            ->update($data);
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