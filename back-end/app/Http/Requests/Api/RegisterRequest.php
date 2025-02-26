<?php

namespace App\Http\Requests\Api;

class RegisterRequest extends ApiRequest
{
    /**
     * Ensure the user details are captured in the registration request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8',
        ];
    }
}
