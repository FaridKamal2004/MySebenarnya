<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Check if tables exist before seeding
        if (!Schema::hasTable('roles') || !Schema::hasTable('permissions')) {
            $this->command->info('Tables for roles and permissions do not exist. Run migrations first.');
            return;
        }

        try {
            // Create roles using firstOrCreate to avoid duplicates
            $publicRole = Role::firstOrCreate(['name' => 'public']);
            $agencyRole = Role::firstOrCreate(['name' => 'agency']);
            $mcmcRole = Role::firstOrCreate(['name' => 'mcmc']);

            // Create permissions using firstOrCreate to avoid duplicates
            $manageUsers = Permission::firstOrCreate(['name' => 'manage users']);
            $manageAgencies = Permission::firstOrCreate(['name' => 'manage agencies']);
            $manageInquiries = Permission::firstOrCreate(['name' => 'manage inquiries']);
            $manageAssignments = Permission::firstOrCreate(['name' => 'manage assignments']);
            $generateReports = Permission::firstOrCreate(['name' => 'generate reports']);
            $viewOwnInquiries = Permission::firstOrCreate(['name' => 'view own inquiries']);
            $createInquiries = Permission::firstOrCreate(['name' => 'create inquiries']);
            $viewAssignedInquiries = Permission::firstOrCreate(['name' => 'view assigned inquiries']);
            $respondToAssignments = Permission::firstOrCreate(['name' => 'respond to assignments']);

            // Sync permissions to roles (removes old ones and adds new ones)
            $publicRole->syncPermissions([
                'view own inquiries',
                'create inquiries',
            ]);

            $agencyRole->syncPermissions([
                'view assigned inquiries',
                'respond to assignments',
            ]);

            $mcmcRole->syncPermissions([
                'manage users',
                'manage agencies',
                'manage inquiries',
                'manage assignments',
                'generate reports',
            ]);

            // Assign mcmc role to user with ID 1 if exists (typically the admin)
            $admin = User::find(1);
            if ($admin) {
                $admin->assignRole('mcmc');
            }

            $this->command->info('Roles and permissions seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Error seeding roles and permissions: ' . $e->getMessage());
        }
    }
}