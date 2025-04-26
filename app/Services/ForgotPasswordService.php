<?php

namespace App\Services;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Models\UserInformation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordService 
{
    public function sendResetEmail($request)
    {
        $userInfo = User::searchUsernameOrEmail($request->username)->first();

        if (!$userInfo) {
            throw new Exception('Email or Username does not exist', 404);
        }

        $newPassword = Str::random(10);

        $userInfo->update([
            'password' => Hash::make($newPassword)
        ]);
     
        Mail::to($userInfo->email)
            ->send(new ForgotPasswordMail($newPassword, $userInfo->userInformation->email));
            
        return true;
        
    }
}