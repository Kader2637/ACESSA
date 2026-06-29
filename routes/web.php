<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/test', [App\Http\Controllers\HomeController::class, 'testEmail']);

Route::get('/zoom-session', function () {
    return view('pages.zoom.meeting');
})->middleware('auth')->name('zoom.session');

Route::get('/login-as-teacher', function () {
    $user = \App\Models\User::first();
    if ($user) {
        Auth::login($user);
        return redirect('/zoom-session?meeting_number=85859134320&passcode=rQP6L2&role=1&course_id=1');
    }
    return "No users found in database.";
});

Route::get('/check-db', function () {
    $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('zoom_meetings', 'passcode');
    return response()->json([
        'passcode_column_exists' => $hasColumn,
        'meetings' => \App\Models\ZoomMeeting::all()
    ]);
});

Route::get('/test-zoom-api', function () {
    $accountId = env('ZOOM_ACCOUNT_ID');
    $clientId = env('ZOOM_CLIENT_ID');
    $clientSecret = env('ZOOM_CLIENT_SECRET');

    $tokenResponse = Http::withoutVerifying()->asForm()->withHeaders([
        'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
    ])->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

    if (!$tokenResponse->successful()) {
        return response()->json(['error' => 'Token request failed', 'body' => $tokenResponse->body()]);
    }

    $accessToken = $tokenResponse->json()['access_token'];

    $meetingResponse = Http::withoutVerifying()->withToken($accessToken)->post('https://api.zoom.us/v2/users/me/meetings', [
        'topic' => 'Diagnostic Test Meeting',
        'type' => 2,
        'start_time' => date('Y-m-d\TH:i:s'),
        'duration' => 60,
        'timezone' => 'Asia/Jakarta'
    ]);

    return response()->json([
        'token_status' => $tokenResponse->status(),
        'meeting_status' => $meetingResponse->status(),
        'meeting_keys' => array_keys($meetingResponse->json() ?? []),
        'meeting_password' => $meetingResponse->json()['password'] ?? 'NOT FOUND',
        'meeting_join_url' => $meetingResponse->json()['join_url'] ?? 'NOT FOUND'
    ]);
});

require_once __DIR__ . '/kader.php';
require_once __DIR__ . '/erik.php';
require_once __DIR__ . '/ivan.php';
require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
require_once __DIR__ . '/authentication.php';
require_once __DIR__ . '/landing.php';
