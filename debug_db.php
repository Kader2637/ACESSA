<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$meetings = \App\Models\ZoomMeeting::all();
foreach ($meetings as $m) {
    echo "ID: {$m->id} | Title: {$m->title} | Link: {$m->zoom_link} | Passcode: " . ($m->passcode ?? 'NULL') . "\n";
}
