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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'edit_products',
            'delete_products',
            'add_products',
            'show_products',
            'set_quantity',
            'edit_users',
            'show_users',
            'delete_users',
            'admin_users',
            'charge_credit',
            'buy_products',
            'give_a_gift'
        ];

        foreach ($permissions as $permissionName) {
            try {
                Permission::create(['name' => $permissionName]);
            } catch (\Exception $e) {
                
            }
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $employeeRole->givePermissionTo([
            'edit_products',
            'add_products',
            'delete_products',
            'show_products',
            'set_quantity',
            'show_users',
            'charge_credit',
            'give_a_gift'
        ]);

  
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
        $customerRole->givePermissionTo([
            'show_products',
            'buy_products',
            'take_a_gift'
        ]);
    }
}
