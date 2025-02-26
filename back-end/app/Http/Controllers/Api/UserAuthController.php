<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Responses\Api\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserAuthController extends Controller
{
    public function register(RegisterRequest $request): ApiResponse
    {
        // Retreive the validated data
        $userDetails = $request->validated();

        // return ApiResponse::success($validated['name']);
        $user = User::create([
            'name' => $userDetails['name'],
            'email' => $userDetails['email'],
            'password' => $userDetails['password'],
        ]);

        // Create a token
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return ApiResponse::success([
            'access_token' => $token,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): ApiResponse
    {
        // Retreive the validated data
        $loginDetails = $request->validated();

        // Find the user
        $user = User::where('email', $loginDetails['email'])->first();

        // If the login details are incorrect, return an error
        if (! $user || ! Hash::check($loginDetails['password'], $user->password)) {
            return ApiResponse::error(401, 'Invalid credentials');
        }

        // Create a token
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return ApiResponse::success([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(): ApiResponse
    {
        CurrentUser()->currentAccessToken()->delete();

        return ApiResponse::success('Successfully logged out.');
    }
}
