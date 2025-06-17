<?php

namespace Database\Seeders;

use App\Models\McmcUser;
use App\Models\RoleUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class McmcUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the MCMC role ID
        $mcmcRole = RoleUser::where('RoleName', 'mcmc')->first();

        // Create a default MCMC admin user
        McmcUser::create([
            'MCMCUserName' => 'MCMC Admin',
            'MCMCEmail' => 'admin@mcmc.gov.my',
            'MCMCPassword' => Hash::make('password'),
            'password' => Hash::make('password'),
            'MCMCContact' => '0123456789',
            'RoleID' => $mcmcRole->RoleID,
        ]);
    }
}