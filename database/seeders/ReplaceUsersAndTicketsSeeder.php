<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\TicketAttachment;

class ReplaceUsersAndTicketsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting ReplaceUsersAndTicketsSeeder...');

        DB::transaction(function () {
            // 1) Remove all tickets and related data
            $this->command->info('Deleting ticket responses, attachments and tickets...');
            TicketResponse::query()->delete();
            TicketAttachment::query()->delete();
            Ticket::query()->delete();

            // Clear notifications (optional)
            if (SchemaExists('notifications')) {
                DB::table('notifications')->delete();
            }

            // 2) Remove all users except existing admins
            $this->command->info('Deleting users except admins...');
            User::where('role', '!=', 'admin')->delete();

            // 3) Insert dummy users from attachment
            $this->command->info('Creating dummy users...');

            $users = [
                ['name' => 'Ismail Wahyudi', 'role_text' => 'Super Admin', 'division' => 'IT Support', 'position' => 'SPV', 'contact' => null],
                ['name' => 'Nasrul Mufid', 'role_text' => 'Admin IT', 'division' => 'IT Support', 'position' => 'Staff', 'contact' => null],
                ['name' => 'Karin', 'role_text' => 'Requester', 'division' => 'Office', 'position' => 'Staff', 'contact' => null],
                ['name' => 'Anggi Anggraini', 'role_text' => 'Requester', 'division' => 'HRD', 'position' => 'SPV', 'contact' => null],
                ['name' => 'Paulina', 'role_text' => 'Requester', 'division' => 'Accounting', 'position' => 'SPV', 'contact' => null],
                ['name' => 'Peggy Anna', 'role_text' => 'Requester', 'division' => 'Sales', 'position' => 'SPV', 'contact' => null],
                ['name' => 'Julianto', 'role_text' => 'Requester', 'division' => 'Warehouse', 'position' => 'SPV', 'contact' => null],
                ['name' => 'Neni', 'role_text' => 'Requester', 'division' => 'Retail/Store', 'position' => 'Staff', 'contact' => null],
                ['name' => 'Sony', 'role_text' => 'Requester', 'division' => 'Logistik', 'position' => 'Staff', 'contact' => null],
                ['name' => 'Alexander', 'role_text' => 'General Manager', 'division' => 'General Manager', 'position' => 'General Manager', 'contact' => null],
                ['name' => 'Christin', 'role_text' => 'Requester', 'division' => 'Office', 'position' => 'Staff', 'contact' => null],
                ['name' => 'Juniarti', 'role_text' => 'Requester', 'division' => 'Office', 'position' => 'Staff', 'contact' => null],
            ];

            foreach ($users as $u) {
                $email = generateEmailFromName($u['name']);
                $role = mapRoleText($u['role_text']);

                // If admin with same email already exists, skip creation
                $existing = User::where('email', $email)->first();
                if ($existing) {
                    $this->command->info("Skipping existing user: {$email}");
                    // Update role/department if needed
                    $existing->update([
                        'role' => $role,
                        'department' => $u['division'] ?? null,
                    ]);
                    continue;
                }

                User::create([
                    'name' => $u['name'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'phone' => $u['contact'],
                    'department' => $u['division'],
                ]);

                $this->command->info("Created user: {$u['name']} <{$email}> as {$role}");
            }
        });

        $this->command->info('ReplaceUsersAndTicketsSeeder finished.');
    }
}

// Helper functions declared outside class to avoid import complexity in seeder file

if (!function_exists('mapRoleText')) {
    function mapRoleText(string $text): string
    {
        $t = strtolower($text);
        if (str_contains($t, 'admin')) {
            return 'admin';
        }
        if (str_contains($t, 'general')) {
            return 'general_manager';
        }
        if (str_contains($t, 'technician') || str_contains($t, 'tech')) {
            return 'technician';
        }
        // default to requester -> user
        return 'user';
    }
}

if (!function_exists('generateEmailFromName')) {
    function generateEmailFromName(string $name): string
    {
        $slug = Str::of($name)->lower()->replaceMatches('/[^a-z0-9]+/', '.')->trim('.');
        return $slug . '@helpdesk.local';
    }
}

if (!function_exists('SchemaExists')) {
    function SchemaExists(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }
}
