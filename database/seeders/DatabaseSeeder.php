<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'General Inquiry', 'description' => 'General inquiries about MCMC services'],
            ['name' => 'Technical Issue', 'description' => 'Technical issues related to telecommunications'],
            ['name' => 'Complaint', 'description' => 'Complaints about service providers'],
            ['name' => 'Suggestion', 'description' => 'Suggestions for improvement'],
            ['name' => 'Other', 'description' => 'Other inquiries'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Run roles and permissions seeder
        $this->call([
            RoleSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
