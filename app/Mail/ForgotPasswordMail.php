<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     */
    public function __construct(public string $token, public string $email)
    {
       
    }

    public function build()
    {
        return $this->to($this->email)
            ->subject('Password Reset')
            ->view('mail.reset_password', [
                'token' => $this->token
            ]);
    }

}
