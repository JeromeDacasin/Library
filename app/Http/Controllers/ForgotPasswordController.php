<?php

namespace App\Http\Controllers;

use App\Services\ForgotPasswordService;
use Exception;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __construct(protected ForgotPasswordService $service)
    {
        
    }

    public function sendResetEmail(Request $request)
    {
        try {
            $this->service->sendResetEmail($request);
            return response()->json([
                'message' => 'A new password has been sent to your email address.',
                'code'    => 200
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code'    => $e->getCode()
            ], $e->getCode());
        }

    }
}
