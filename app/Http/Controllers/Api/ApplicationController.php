<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Application::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $application = Application::create($request->all());

        return response()->json([
            'application_id' => $application->application_id,
            'applicant_id' => $application->applicant_id,
            'scholarship_id' => $application->scholarship_id,
            'date_applied' => $application->date_applied,
            'status' => $application->status,
            'remarks' => $application->remarks,
            'created_at' => $application->created_at,
            'updated_at' => $application->updated_at
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = Application::findOrFail($id);

        return response()->json($application);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        $application->update($request->all());

        return response()->json([
            'message' => 'Application updated successfully',
            'data' => $application
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $application = Application::findOrFail($id);

        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully'
        ]);
    }
}