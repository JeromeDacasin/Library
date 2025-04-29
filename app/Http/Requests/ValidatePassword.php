<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ValidatePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           
            'new' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ]
    
        ];
    }

    public function messages()
    {
        return [
            'new.required' => 'The new password field is required.',
            'new.min' => 'The new password must be at least 8 characters.',
            'new.mixedCase' => 'The new password must contain both uppercase and lowercase letters.',
            'new.letters' => 'The new password must contain at least one letter.',
            'new.numbers' => 'The new password must include at least one number.',
            'new.symbols' => 'The new password must contain at least one symbol.',
        ];
    }
}
