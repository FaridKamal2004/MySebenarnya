<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles using firstOrCreate to avoid duplicates
        $mcmcRole = Role::firstOrCreate(['name' => 'mcmc']);
        $agencyRole = Role::firstOrCreate(['name' => 'agency']);
        $publicRole = Role::firstOrCreate(['name' => 'public']);

        // Create a test agency
        $agency = Agency::create([
            'name' => 'Test Agency',
            'contact' => 'Agency Contact',
            'email' => 'agency@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Agency Street, City',
        ]);

        // Create test users for each role
        $mcmcUser = User::create([
            'name' => 'MCMC Admin',
            'email' => 'mcmc@example.com',
            'password' => bcrypt('password'),
        ]);
        $mcmcUser->assignRole($mcmcRole);

        $agencyUser = User::create([
            'name' => 'Agency User',
            'email' => 'agency_user@example.com',
            'password' => bcrypt('password'),
            'agency_id' => $agency->id,
        ]);
        $agencyUser->assignRole($agencyRole);

        $publicUser = User::create([
            'name' => 'Public User',
            'email' => 'public@example.com',
            'password' => bcrypt('password'),
        ]);
        $publicUser->assignRole($publicRole);
    }
}