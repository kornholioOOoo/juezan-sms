<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        return Student::all();
    }

    public function store(Request $request)
    {
        $student = Student::create($request->all());

        return response()->json([
            'student_id' => $student->student_id,
            'user_id' => $student->user_id,
            'student_no' => $student->student_no,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
            'course' => $student->course,
            'year_level' => $student->year_level,
            'contact_no' => $student->contact_no,
            'address' => $student->address
        ], 201);
    }

    public function show(string $student_id)
    {
        $student = Student::findOrFail($student_id);
        return response()->json($student);
    }

    public function update(Request $request, string $student_id)
    {
        $student = Student::findOrFail($student_id);
        $student->update($request->all());

        return response()->json([
            'message' => 'Student updated successfully',
            'data' => $student
        ]);
    }

    public function destroy(string $student_id)
    {
        $student = Student::findOrFail($student_id);
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully'
        ]);
    }
}