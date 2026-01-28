<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'department' => 'IT',
        ]);

        // General Manager
        User::create([
            'name' => 'General Manager',
            'email' => 'gm@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'general_manager',
            'phone' => '081234567899',
            'department' => 'Management',
        ]);

        // Technician
        User::create([
            'name' => 'Teknisi 1',
            'email' => 'tech1@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
            'phone' => '081234567891',
            'department' => 'IT Support',
        ]);

        User::create([
            'name' => 'Teknisi 2',
            'email' => 'tech2@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
            'phone' => '081234567892',
            'department' => 'IT Support',
        ]);

        // Regular Users
        User::create([
            'name' => 'User Demo',
            'email' => 'user@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567893',
            'department' => 'Finance',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567894',
            'department' => 'Marketing',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567895',
            'department' => 'HR',
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567896',
            'department' => 'Sales',
        ]);
    }
}