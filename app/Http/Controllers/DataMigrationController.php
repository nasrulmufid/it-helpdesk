<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataMigrationController extends Controller
{
    public function index()
    {
        return view('admin.import-export');
    }

    public function downloadTemplate($type)
    {
        $headers = [];
        $sample = [];

        switch ($type) {
            case 'users':
                $headers = ['name', 'email', 'password', 'role', 'phone', 'department'];
                $sample = ['John Doe', 'john@example.com', 'password123', 'user', '08123456789', 'IT Support'];
                break;
            case 'categories':
                $headers = ['name', 'description', 'icon', 'is_active'];
                $sample = ['Hardware', 'Masalah perangkat keras', '💻', '1'];
                break;
            case 'tickets':
                $headers = ['title', 'description', 'user_email', 'category_name', 'priority', 'status', 'assigned_to_email'];
                $sample = ['Printer Rusak', 'Printer di lantai 2 macet', 'user@example.com', 'Hardware', 'high', 'open', 'tech@example.com'];
                break;
            default:
                abort(404);
        }

        $filename = "template_{$type}.csv";
        
        return response()->streamDownload(function () use ($headers, $sample) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fputcsv($handle, $sample);
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function export($type)
    {
        $filename = "export_{$type}_" . date('Y-m-d_H-i-s') . ".csv";
        
        return response()->streamDownload(function () use ($type) {
            $handle = fopen('php://output', 'w');
            
            if ($type === 'users') {
                fputcsv($handle, ['name', 'email', 'role', 'phone', 'department', 'created_at']);
                User::chunk(100, function ($users) use ($handle) {
                    foreach ($users as $user) {
                        fputcsv($handle, [
                            $user->name,
                            $user->email,
                            $user->role,
                            $user->phone,
                            $user->department,
                            $user->created_at
                        ]);
                    }
                });
            } elseif ($type === 'categories') {
                fputcsv($handle, ['name', 'slug', 'description', 'icon', 'is_active', 'created_at']);
                Category::chunk(100, function ($categories) use ($handle) {
                    foreach ($categories as $category) {
                        fputcsv($handle, [
                            $category->name,
                            $category->slug,
                            $category->description,
                            $category->icon,
                            $category->is_active,
                            $category->created_at
                        ]);
                    }
                });
            } elseif ($type === 'tickets') {
                fputcsv($handle, ['ticket_number', 'title', 'description', 'user_email', 'category_name', 'priority', 'status', 'assigned_to_email', 'created_at']);
                Ticket::with(['user', 'category', 'assignedTo'])->chunk(100, function ($tickets) use ($handle) {
                    foreach ($tickets as $ticket) {
                        fputcsv($handle, [
                            $ticket->ticket_number,
                            $ticket->title,
                            $ticket->description,
                            $ticket->user->email ?? '',
                            $ticket->category->name ?? '',
                            $ticket->priority,
                            $ticket->status,
                            $ticket->assignedTo->email ?? '',
                            $ticket->created_at
                        ]);
                    }
                });
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request, $type)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        
        // Skip header
        fgetcsv($handle);
        
        DB::beginTransaction();
        
        try {
            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                if ($type === 'users') {
                    $this->importUser($row, $rowNumber);
                } elseif ($type === 'categories') {
                    $this->importCategory($row, $rowNumber);
                } elseif ($type === 'tickets') {
                    $this->importTicket($row, $rowNumber);
                }
            }
            
            DB::commit();
            fclose($handle);
            
            return back()->with('success', "Data {$type} berhasil diimport!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', "Gagal import baris ke-{$rowNumber}: " . $e->getMessage());
        }
    }

    private function importUser($row, $rowNumber)
    {
        if (count($row) < 6) {
            throw new \Exception("Format kolom tidak sesuai. Harusnya: name, email, password, role, phone, department");
        }

        $email = $row[1];
        if (User::where('email', $email)->exists()) {
            // Update existing user or skip? Let's skip for now to avoid overwriting password
            // Or maybe update non-sensitive fields
            return; 
        }

        User::create([
            'name' => $row[0],
            'email' => $email,
            'password' => Hash::make($row[2]),
            'role' => $this->validateRole($row[3]),
            'phone' => $row[4],
            'department' => $row[5],
        ]);
    }

    private function importCategory($row, $rowNumber)
    {
        if (count($row) < 4) {
            throw new \Exception("Format kolom tidak sesuai. Harusnya: name, description, icon, is_active");
        }

        $name = $row[0];
        $slug = Str::slug($name);

        Category::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'description' => $row[1],
                'icon' => $row[2],
                'is_active' => (bool)$row[3],
            ]
        );
    }

    private function importTicket($row, $rowNumber)
    {
        $columnCount = count($row);
        $isExportFormat = $columnCount >= 9;

        if (!$isExportFormat && $columnCount < 7) {
            throw new \Exception("Format kolom tidak sesuai. Harusnya: title, description, user_email, category_name, priority, status, assigned_to_email");
        }

        $ticketNumberFromCsv = null;
        if ($isExportFormat) {
            $ticketNumberFromCsv = trim((string) $row[0]);
            $title = $row[1];
            $description = $row[2];
            $userEmail = $row[3];
            $categoryName = $row[4];
            $priority = $row[5];
            $status = $row[6];
            $assignedToEmail = $row[7] ?? null;
        } else {
            $title = $row[0];
            $description = $row[1];
            $userEmail = $row[2];
            $categoryName = $row[3];
            $priority = $row[4];
            $status = $row[5];
            $assignedToEmail = $row[6] ?? null;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            throw new \Exception("User dengan email {$userEmail} tidak ditemukan");
        }

        $category = Category::where('name', $categoryName)->first();
        if (!$category) {
            // Try to find by slug or create? Let's throw error for strictness
             throw new \Exception("Kategori '{$categoryName}' tidak ditemukan");
        }

        $assignedTo = null;
        if (!empty($assignedToEmail)) {
            $assignedUser = User::where('email', $assignedToEmail)->first();
            if ($assignedUser) {
                $assignedTo = $assignedUser->id;
            }
        }

        $attributes = [
            'title' => $title,
            'description' => $description,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'priority' => $this->validatePriority($priority),
            'status' => $this->validateStatus($status),
            'assigned_to' => $assignedTo,
        ];

        if (!empty($ticketNumberFromCsv) && !Ticket::where('ticket_number', $ticketNumberFromCsv)->exists()) {
            $attributes['ticket_number'] = $ticketNumberFromCsv;
        }

        Ticket::createWithUniqueTicketNumber($attributes);
    }

    private function validateRole($role)
    {
        $validRoles = ['admin', 'technician', 'user', 'general_manager'];
        return in_array($role, $validRoles) ? $role : 'user';
    }

    private function validatePriority($priority)
    {
        $valid = ['low', 'medium', 'high', 'critical'];
        return in_array($priority, $valid) ? $priority : 'low';
    }

    private function validateStatus($status)
    {
        $valid = ['open', 'in_progress', 'resolved', 'closed'];
        return in_array($status, $valid) ? $status : 'open';
    }
}
