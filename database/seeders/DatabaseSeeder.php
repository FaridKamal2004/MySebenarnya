<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\Category;
=======
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
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
=======
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
        ]);
    }
}
