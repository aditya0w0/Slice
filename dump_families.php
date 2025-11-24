<?php

use App\Models\Device;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$families = Device::distinct()->pluck('family');
echo json_encode($families);
