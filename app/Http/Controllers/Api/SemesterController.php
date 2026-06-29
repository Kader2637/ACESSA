<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Semester;

class SemesterController extends Controller
{
    /**
     * Get list of semesters ordered by semester number
     */
    public function index()
    {
        $semesters = Semester::orderBy('semester_number', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $semesters
        ], 200);
    }
}
