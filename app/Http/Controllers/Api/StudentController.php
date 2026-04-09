<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display all students
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Student::with('user')->get()
        ]);
    }

    /**
     * Store a newly created student (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            // User info
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',

            // Student info
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'course' => 'required|string|max:50',
            'year_level' => 'required|integer|min:1|max:5',
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $now = Carbon::now();

        // 1️⃣ Create the user automatically
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2️⃣ Create the linked student record
        $student = Student::create([
            'user_id' => $user->user_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'course' => $request->course,
            'year_level' => $request->year_level,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student added successfully',
            'data' => [
                'student' => $student,
                'user' => $user
            ]
        ], 201);
    }

    /**
     * Show single student
     */
    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $request->validate([
            // Student info
            'first_name' => 'sometimes|string|max:255',
            'middle_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'course' => 'sometimes|string|max:50',
            'year_level' => 'sometimes|integer|min:1|max:5',
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',

            // User info
            'username' => 'sometimes|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'sometimes|string|min:6',
        ]);

        // Update student
        $student->update($request->only([
            'first_name','middle_name','last_name','course','year_level','contact_no','address'
        ]));

        // Update user
        $userUpdate = $request->only(['username','email']);
        if ($request->filled('password')) {
            $userUpdate['password'] = Hash::make($request->password);
        }
        $user->update($userUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => [
                'student' => $student,
                'user' => $user
            ]
        ]);
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete(); // cascades and deletes user if foreign key set
        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }
}