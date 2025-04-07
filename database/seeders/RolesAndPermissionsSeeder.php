<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // Create permissions
        Permission::create(['name' => 'edit_products']);
        Permission::create(['name' => 'delete_products']);
        Permission::create(['name' => 'add_products']);
        Permission::create(['name' => 'show_products']);
        Permission::create(['name' => 'set_quantity']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'show_users']);
        Permission::create(['name' => 'delete_users']);
        Permission::create(['name' => 'admin_users']);
        Permission::create(['name' => 'charge_credit']);
        Permission::create(['name' => 'buy_products']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $employeeRole = Role::create(['name' => 'Employee']);
        $employeeRole->givePermissionTo([
            'edit_products',
            'add_products',
            'delete_products',
            'show_products',
            'set_quantity',
            'show_users',
            'charge_credit'
        ]);

        $customerRole = Role::create(['name' => 'Customer']);
        $customerRole->givePermissionTo([
            'show_products',
            'buy_products'
        ]);
    }
}
