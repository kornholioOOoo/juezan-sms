<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications
     * - Admin: see all
     * - Student: see own only
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $applications = Application::with(['student', 'scholarship'])->get();
        } else {
            $student = $user->student;
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found'
                ], 404);
            }
            $applications = Application::with('scholarship')
                ->where('applicant_id', $student->student_id)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }

    /**
     * Store a newly created application (student only)
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

        // Prevent duplicate applications
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
            'applicant_id' => $student->student_id,
            'scholarship_id' => $request->scholarship_id,
            'status' => 'pending',
            'date_applied' => now()
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
        $application = Application::with(['student', 'scholarship'])->findOrFail($id);
        $user = $request->user();

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
     * Update application (student only, can change scholarship)
     */
    public function update(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        $user = $request->user();

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
     * Delete application (student or admin)
     */
    public function destroy(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        $user = $request->user();

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

    /**
     * Approve an application (admin only)
     */
    public function approve(string $id)
    {
        $application = Application::findOrFail($id);

        $application->update([
            'status' => 'approved',
            'remarks' => 'Approved by admin'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application approved successfully',
            'data' => $application
        ]);
    }

    /**
     * Reject an application (admin only)
     */
    public function reject(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        $remarks = $request->input('remarks', 'Rejected by admin');

        $application->update([
            'status' => 'rejected',
            'remarks' => $remarks
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application rejected successfully',
            'data' => $application
        ]);
    }
}