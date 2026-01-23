<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@helpdesk.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'department' => 'IT',
            ]
        );

        User::firstOrCreate(
            ['email' => 'gm@helpdesk.com'],
            [
                'name' => 'General Manager',
                'password' => Hash::make('password'),
                'role' => 'general_manager',
                'phone' => '081234567899',
                'department' => 'Management',
            ]
        );

        User::firstOrCreate(
            ['email' => 'tech1@helpdesk.com'],
            [
                'name' => 'Teknisi 1',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '081234567891',
                'department' => 'IT Support',
            ]
        );

        User::firstOrCreate(
            ['email' => 'tech2@helpdesk.com'],
            [
                'name' => 'Teknisi 2',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '081234567892',
                'department' => 'IT Support',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@helpdesk.com'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '081234567893',
                'department' => 'Finance',
            ]
        );

        User::firstOrCreate(
            ['email' => 'john@helpdesk.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '081234567894',
                'department' => 'Marketing',
            ]
        );

        User::firstOrCreate(
            ['email' => 'jane@helpdesk.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '081234567895',
                'department' => 'HR',
            ]
        );

        User::firstOrCreate(
            ['email' => 'bob@helpdesk.com'],
            [
                'name' => 'Bob Wilson',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '081234567896',
                'department' => 'Sales',
            ]
        );
    }
}
