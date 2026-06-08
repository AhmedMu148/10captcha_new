<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the admin seeder
        $this->call(AdminUserSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(CustomImageSeeder::class);





    }
}
