<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$accountId = env('ZOOM_ACCOUNT_ID');
$clientId = env('ZOOM_CLIENT_ID');
$clientSecret = env('ZOOM_CLIENT_SECRET');

echo "Requesting Token...\n";
$tokenResponse = Http::withoutVerifying()->asForm()->withHeaders([
    'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
])->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

if (!$tokenResponse->successful()) {
    echo "Token failed: " . $tokenResponse->body() . "\n";
    exit;
}

$accessToken = $tokenResponse->json()['access_token'];
echo "Token Success: " . substr($accessToken, 0, 10) . "...\n";

echo "Creating Meeting...\n";
$meetingResponse = Http::withoutVerifying()->withToken($accessToken)->post('https://api.zoom.us/v2/users/me/meetings', [
    'topic' => 'Diagnostic Test Meeting',
    'type' => 2,
    'start_time' => date('Y-m-d\TH:i:s'),
    'duration' => 60,
    'timezone' => 'Asia/Jakarta'
]);

echo "Meeting Response Status: " . $meetingResponse->status() . "\n";
echo "Meeting Response Keys: " . implode(', ', array_keys($meetingResponse->json() ?? [])) . "\n";
echo "Meeting Response Password: " . ($meetingResponse->json()['password'] ?? 'NOT FOUND') . "\n";
echo "Meeting Response Join URL: " . ($meetingResponse->json()['join_url'] ?? 'NOT FOUND') . "\n";
