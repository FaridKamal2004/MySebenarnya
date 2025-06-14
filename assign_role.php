<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Get the user
$user = User::find(1);

if ($user) {
    echo "Current user: " . $user->name . " (" . $user->email . ")\n";
    echo "Current roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    
    // Assign MCMC role
    $mcmcRole = Role::where('name', 'mcmc')->first();
    if ($mcmcRole) {
        $user->assignRole($mcmcRole);
        echo "Assigned MCMC role to user.\n";
    } else {
        echo "MCMC role not found.\n";
    }
    
    // Refresh user
    $user = User::find(1);
    echo "Updated roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
} else {
    echo "User with ID 1 not found.\n";
}