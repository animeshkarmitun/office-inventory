<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Testing Login System\n";
echo "===================\n\n";

// Check existing users
$users = User::all();
echo "Found " . $users->count() . " users in database:\n";

foreach ($users as $user) {
    echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

echo "\n";

// Test authentication with first user
if ($users->count() > 0) {
    $testUser = $users->first();
    echo "Testing authentication for user: {$testUser->email}\n";
    
    // Try to authenticate with a test password
    $testPassword = 'password123';
    
    if (Hash::check($testPassword, $testUser->password)) {
        echo "✓ Password 'password123' works for this user\n";
    } else {
        echo "✗ Password 'password123' does not work for this user\n";
        echo "  (This is normal if the user was created with a different password)\n";
    }
    
    // Check if password field is properly hashed
    if (strlen($testUser->password) > 20) {
        echo "✓ Password appears to be properly hashed\n";
    } else {
        echo "✗ Password does not appear to be hashed properly\n";
    }
}

echo "\nTest completed.\n"; 