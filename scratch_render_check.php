<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$donasis = \App\Models\Donasi::with(['lokasi', 'user'])->get();
$json = json_encode($donasis, JSON_PRETTY_PRINT);
file_put_contents('scratch_donasis_json.txt', $json);
echo "Written donasis JSON to scratch_donasis_json.txt. Total: " . count($donasis) . PHP_EOL;
