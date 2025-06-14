<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Get all users
$users = User::all();

echo "User Roles:\n";
echo "===========\n";

foreach ($users as $user) {
    echo "User ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    echo "Has MCMC Role: " . ($user->hasRole('mcmc') ? 'Yes' : 'No') . "\n";
    echo "Has Agency Role: " . ($user->hasRole('agency') ? 'Yes' : 'No') . "\n";
    echo "Has Public Role: " . ($user->hasRole('public') ? 'Yes' : 'No') . "\n";
    echo "===========\n";
}