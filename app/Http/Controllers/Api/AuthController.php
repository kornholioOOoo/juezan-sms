<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register a new user (Admin or Student)
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // password_confirmation required
            'role' => 'required|string|in:admin,student',
            'first_name' => 'required_if:role,student|string|max:255',
            'middle_name' => 'required_if:role,student|string|max:255',
            'last_name' => 'required_if:role,student|string|max:255',
        ]);

        $now = Carbon::now();

        // Create user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // If the user is a student, create a record in students table
        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->user_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Login user (Admin or Student)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Delete the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Return authenticated user info
     */
    public function me(Request $request)
    {
        $user = $request->user();

        // If the user is a student, include student info
        if ($user->role === 'student') {
            $student = $user->student; // uses relation: User hasOne Student
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'student' => $student
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}