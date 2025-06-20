<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthenticationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ]);

            return $this->successWithData([
                'user' => $user,
            ], 'User registered successfully', 201);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();

            // Check if the user exists usine email or username
            $user = User::where('email', $validated['email'])
                ->orWhere('username', $validated['email'])
                ->first();

            if (!$user) throw new AuthenticationException("User not registered");

            if (!Hash::check($validated['password'], $user->password)) {
                throw new AuthenticationException("Invalid credentials");
            }

            // Optionally, you can generate a token or session here
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successWithData([
                'token' => $token,
            ], 'User logged in successfully');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function me() {
        try {
            $user = Auth::user();
            if (!$user) {
                throw new AuthenticationException("User not authenticated or session expired");
            }
            return $this->successWithData([
                'user' => $user,
            ], 'User retrieved successfully');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
