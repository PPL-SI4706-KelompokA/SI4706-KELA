<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$emails = ['admin_test@email.com', 'donatur_test@email.com', 'penerima_test@email.com'];
foreach ($emails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        $check = Hash::check('12345678', $user->password) ? 'VALID' : 'INVALID';
        echo "User: {$user->email} | Role: {$user->role} | Verif: {$user->status_verifikasi} | Password: {$check} | Hash: {$user->password}" . PHP_EOL;
    } else {
        echo "User not found: {$email}" . PHP_EOL;
    }
}











