<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin: see all applications
        if ($user->role === 'admin') {
            return response()->json([
                'success' => true,
                'data' => Application::all()
            ]);
        }

        // Student: see only own applications
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found'
            ], 404);
        }

        $applications = Application::where('applicant_id', $student->student_id)->get();

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }

    /**
     * Store a newly created application
     */
    public function store(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,scholarship_id'
        ]);

        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found'
            ], 404);
        }

        // Prevent duplicate application
        $existing = Application::where('applicant_id', $student->student_id)
            ->where('scholarship_id', $request->scholarship_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied to this scholarship'
            ], 409);
        }

        $application = Application::create([
            'applicant_id' => $student->student_id, // ✅ automatic
            'scholarship_id' => $request->scholarship_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'data' => $application
        ], 201);
    }

    /**
     * Display a specific application
     */
    public function show(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        $user = $request->user();

        // If not admin, restrict access
        if ($user->role !== 'admin') {
            $student = $user->student;

            if (!$student || $application->applicant_id != $student->student_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $application
        ]);
    }

    /**
     * Update application
     */
    public function update(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        $user = $request->user();

        // Only owner or admin can update
        if ($user->role !== 'admin') {
            $student = $user->student;

            if (!$student || $application->applicant_id != $student->student_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        $request->validate([
            'scholarship_id' => 'sometimes|exists:scholarships,scholarship_id'
        ]);

        $application->update([
            'scholarship_id' => $request->scholarship_id ?? $application->scholarship_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application updated successfully',
            'data' => $application
        ]);
    }

    /**
     * Delete application
     */
    public function destroy(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        $user = $request->user();

        // Only owner or admin can delete
        if ($user->role !== 'admin') {
            $student = $user->student;

            if (!$student || $application->applicant_id != $student->student_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Application deleted successfully'
        ]);
    }
}