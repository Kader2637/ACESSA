<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LiveCaption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveCaptionController extends Controller
{
    /**
     * Store a new live caption segment
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'text' => 'required|string',
            'language' => 'required|string',
        ]);

        $caption = LiveCaption::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::check() ? Auth::id() : 1, // Fallback for safety
            'text' => $request->text,
            'language' => $request->language,
        ]);

        // Clean up captions older than 2 hours to avoid database bloat
        LiveCaption::where('created_at', '<', now()->subHours(2))->delete();

        return response()->json([
            'success' => true,
            'data' => $caption
        ], 200);
    }

    /**
     * Retrieve latest captions for a course (polling mechanism)
     */
    public function getCaptions(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
        ]);

        $courseId = $request->query('course_id');
        $lastId = $request->query('last_id', 0);

        $captions = LiveCaption::where('course_id', $courseId)
            ->where('id', '>', $lastId)
            ->with('user:id,name')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $captions
        ], 200);
    }
}
