<?php

namespace Database\Seeders;

use App\Models\McmcUser;
use App\Models\RoleUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestMcmcUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the MCMC role ID
        $mcmcRole = RoleUser::where('RoleName', 'mcmc')->first();

        // Create a test MCMC user
        McmcUser::create([
            'MCMCUserName' => 'MCMC Test User',
            'MCMCEmail' => 'test@mcmc.gov.my',
            'MCMCPassword' => Hash::make('password123'),
            'password' => Hash::make('password123'),
            'MCMCContact' => '0123456789',
            'RoleID' => $mcmcRole->RoleID,
        ]);
    }
}