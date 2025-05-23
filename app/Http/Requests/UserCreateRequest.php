<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|unique:user_informations,email|regex:/^[A-Za-zñÑ0-9.-_]+@[A-Za-z0-9._-]+\.[A-Za-z]+$/',
            'birth_date' => 'required|date',
            'role_id'    => 'required|int|exists:roles,id',
        ];
    }
}
