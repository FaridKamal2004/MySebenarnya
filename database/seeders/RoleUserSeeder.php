<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the three roles required by the system
        RoleUser::create([
            'RoleName' => 'public'
        ]);

        RoleUser::create([
            'RoleName' => 'agency'
        ]);

        RoleUser::create([
            'RoleName' => 'mcmc'
        ]);
    }
}