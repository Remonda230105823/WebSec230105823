<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ProductsSeeder::class);

        // Create Admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'credits' => 1000,
        ]);
        $admin->assignRole('Admin');

        // Create Employee user
        $employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'credits' => 500,
        ]);
        $employee->assignRole('Employee');

        // Create Customer user
        $customer = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'credits' => 200,
        ]);
        $customer->assignRole('Customer');
    }
}
