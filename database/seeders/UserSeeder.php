<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (seeded only — no self-registration)
        $superAdmin = User::updateOrCreate([
            'email' => 'admin@auacollection.com',
        ], [
            'name' => 'AUA Super Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->syncRoles(['super-admin']);

        // Customer demo account
        $customer = User::updateOrCreate([
            'email' => 'customer@example.com',
        ], [
            'name' => 'John Doe',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $customer->syncRoles(['customer']);
    }
}
