<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage_dashboard',
            'manage_products',
            'manage_categories',
            'manage_collections',
            'manage_brands',
            'manage_sizes',
            'manage_colors',
            'manage_inventory',
            'manage_customers',
            'manage_orders',
            'manage_payments',
            'manage_reviews',
            'manage_coupons',
            'manage_homepage',
            'manage_settings',
            'manage_shipping',
            'manage_reports',
            'manage_users',
            'manage_roles',
            'manage_notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $all = Permission::all();

        $superAdmin = Role::findOrCreate('super-admin');
        $superAdmin->syncPermissions($all);

        $admin = Role::findOrCreate('admin');
        $admin->syncPermissions($all);

        $storeManager = Role::findOrCreate('store-manager');
        $storeManager->syncPermissions([
            'manage_dashboard',
            'manage_products',
            'manage_categories',
            'manage_collections',
            'manage_orders',
            'manage_customers',
            'manage_coupons',
            'manage_homepage',
            'manage_reviews',
        ]);

        $inventoryManager = Role::findOrCreate('inventory-manager');
        $inventoryManager->syncPermissions([
            'manage_dashboard',
            'manage_products',
            'manage_inventory',
            'manage_sizes',
            'manage_colors',
            'manage_brands',
        ]);

        $customerSupport = Role::findOrCreate('customer-support');
        $customerSupport->syncPermissions([
            'manage_dashboard',
            'manage_orders',
            'manage_customers',
            'manage_reviews',
            'manage_notifications',
        ]);

        Role::findOrCreate('customer');
    }
}
