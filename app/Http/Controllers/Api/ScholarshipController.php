<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Scholarship;

class ScholarshipController extends Controller
{

    public function index()
    {
        return Scholarship::all();
    }

    public function store(Request $request)
    {
        $scholarship = Scholarship::create($request->all());

        return response()->json([
            'scholarship_id' => $scholarship->scholarship_id,
            'scholarship_name' => $scholarship->scholarship_name,
            'provider' => $scholarship->provider,
            'description' => $scholarship->description,
            'amount' => $scholarship->amount,
            'slots' => $scholarship->slots,
            'status' => $scholarship->status
        ], 201);
    }


    public function show(string $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        return response()->json($scholarship);
    }


    public function update(Request $request, string $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $scholarship->update($request->all());

        return response()->json([
            'message' => 'Scholarship updated successfully',
            'data' => $scholarship
        ]);
    }


    public function destroy(string $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $scholarship->delete();

        return response()->json([
            'message' => 'Scholarship deleted successfully'
        ]);
    }
}