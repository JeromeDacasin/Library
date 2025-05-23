<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInformation;
use Exception;
use Illuminate\Support\Facades\Auth;
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
        $role = roles($request->role_id);

        $username = stristr($role, 'student') ? $this->studentUsername($request) : $this->generateUserName($request);
        $this->checkForExistingUserName($username);

        $password = $this->generatePassword();

        $user = $this->user::create([
            'username' => $request->role_id === 2 ? ($request->student_number ?? $username) : null,
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
            'is_generated_student_number' => $request->is_generated_student_number,
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
            'role'   => $user->role->name,
            'gender' => $user->userInformation->gender,
            'full_name' => ucwords($user->userInformation->first_name . ' ' . $user->userInformation->last_name)
            
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
        return $request->first_name . $request->last_name;
    }

    public function generateStudentNumber() : string
    {   
        $latestUser = $this->user::isGenerated()->latest()->first();
        $username = $latestUser->username;
        

        $newUsername = explode('-', $username)[1] + 1;

        $generatedUsername = now()->format('y') . '-' . str_pad($newUsername, 5, '0', STR_PAD_LEFT);

        return $generatedUsername;
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
        $user = $this->user->query()
            ->join('user_informations', 'users.id', '=', 'user_informations.user_id')
            ->select('users.*')
            ->with('userInformation')
            ->orderBy('user_informations.last_name');
        
        $user = $user->where('role_id', $request->role_id);
        
        $search  = $request->search;
        if ($request->search) {
            $user = $user->search($search);
        }

        return $request->paginate ? $user->paginate(10) : $user->get();   

   
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

    public function updatePassword($request) 
    {
        $auth = Auth::user();

        if (!Hash::check($request->current, $auth->password)) {
            throw new Exception('Current Password is Incorrect', 422);
        }

        if (strcmp($request->current, $request->new) === 0) {
          
            throw new Exception('New Password cannot be same as your current password.', 422);
        }

        $user = $this->user::find($auth->id);

        $user->password = Hash::make($request->new);

        $user = $user->save();

        return $user;
    }

    private function studentUsername($request) : string 
    {
        $username =  empty($request->student_number)
            ? $this->generateStudentNumber()
            : $request->student_number;
       
        return $username;
    }


}