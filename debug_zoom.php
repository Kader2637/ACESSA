<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$clientId = env('ZOOM_SDK_KEY');
$clientSecret = env('ZOOM_SDK_SECRET');

echo "Client ID: $clientId\n";
echo "Client Secret: $clientSecret\n";

$tokenResponse = Http::asForm()->withHeaders([
    'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
])->post('https://zoom.us/oauth/token', [
    'grant_type' => 'client_credentials',
]);

echo "Status Code: " . $tokenResponse->status() . "\n";
echo "Response Body: " . $tokenResponse->body() . "\n";
