<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencies = [
            [
                'name' => 'Ministry of Health',
                'contact' => 'Dr. Ahmad',
                'email' => 'contact@moh.gov.my',
                'phone' => '03-8000-8000',
                'address' => 'Putrajaya, Malaysia',
            ],
            [
                'name' => 'Ministry of Education',
                'contact' => 'Dr. Sarah',
                'email' => 'contact@moe.gov.my',
                'phone' => '03-8000-8001',
                'address' => 'Putrajaya, Malaysia',
            ],
            [
                'name' => 'Royal Malaysia Police',
                'contact' => 'Inspector Rizal',
                'email' => 'contact@pdrm.gov.my',
                'phone' => '03-8000-8002',
                'address' => 'Bukit Aman, Kuala Lumpur',
            ],
        ];

        foreach ($agencies as $agencyData) {
            $agency = Agency::create($agencyData);
            
            // Create a user for each agency
            $user = User::create([
                'name' => 'User ' . $agency->name,
                'email' => 'user@' . strtolower(str_replace(' ', '', $agency->name)) . '.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'agency_id' => $agency->id,
            ]);

            $user->assignRole('agency');
        }
    }
}