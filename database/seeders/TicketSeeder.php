<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;
use App\Models\Category;
use App\Notifications\TicketActivityNotification;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $technicians = User::where('role', 'technician')->get();
        $categories = Category::all();
        $admins = User::whereIn('role', ['admin', 'general_manager'])->get();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Please run UserSeeder and CategorySeeder first!');
            return;
        }

        $priorities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        // Create 20 tickets with various statuses
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $category = $categories->random();
            $priority = $priorities[array_rand($priorities)];
            $status = $statuses[array_rand($statuses)];
            
            // Randomly assign technician for some tickets
            $assignedTo = ($i % 3 == 0) ? $technicians->random()->id : null;

            // Generate unique ticket number
            $ticketNumber = 'TKT' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT);

            $ticket = Ticket::create([
                'ticket_number' => $ticketNumber,
                'title' => $this->getTicketTitle($i),
                'description' => $this->getTicketDescription($i),
                'user_id' => $user->id,
                'category_id' => $category->id,
                'assigned_to' => $assignedTo,
                'priority' => $priority,
                'status' => $status,
                'created_at' => now()->subDays(rand(0, 20)),
                'resolved_at' => ($status == 'resolved' || $status == 'closed') ? now()->subDays(rand(0, 5)) : null,
                'closed_at' => ($status == 'closed') ? now()->subDays(rand(0, 3)) : null,
            ]);

            // Create some responses
            if ($i % 2 == 0) {
                TicketResponse::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $assignedTo ?? $technicians->random()->id,
                    'message' => 'Kami sedang menangani masalah ini. Mohon ditunggu.',
                    'is_internal' => false,
                    'created_at' => $ticket->created_at->addHours(2),
                ]);
            }

            // Create notification for ticket creation (for recent tickets)
            if ($i <= 10) {
                $recipients = $admins->merge($technicians);
                foreach ($recipients as $recipient) {
                    $recipient->notify(new TicketActivityNotification([
                        'type' => 'tiket_baru',
                        'ticket_id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'title' => 'Tiket baru dibuat',
                        'message' => sprintf('Tiket %s dibuat oleh %s dengan prioritas %s', $ticket->ticket_number, $user->name, ucfirst($priority)),
                        'url' => route('tickets.show', $ticket),
                        'by_user_id' => $user->id,
                        'by_user_name' => $user->name,
                    ]));
                }
            }

            // Create notification for assignment
            if ($assignedTo && $i <= 12) {
                $assignedUser = User::find($assignedTo);
                $assignedUser->notify(new TicketActivityNotification([
                    'type' => 'tiket_ditugaskan',
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'title' => 'Tiket ditugaskan',
                    'message' => sprintf('Tiket %s ditugaskan kepada Anda', $ticket->ticket_number),
                    'url' => route('tickets.show', $ticket),
                    'by_user_id' => 1, // Admin
                    'by_user_name' => 'Administrator',
                ]));

                // Notify ticket owner
                $user->notify(new TicketActivityNotification([
                    'type' => 'tiket_ditugaskan',
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'title' => 'Tiket ditugaskan',
                    'message' => sprintf('Tiket %s ditugaskan kepada %s', $ticket->ticket_number, $assignedUser->name),
                    'url' => route('tickets.show', $ticket),
                    'by_user_id' => 1,
                    'by_user_name' => 'Administrator',
                ]));
            }

            // Create notification for status change (resolved/closed)
            if (in_array($status, ['resolved', 'closed']) && $i <= 8) {
                $user->notify(new TicketActivityNotification([
                    'type' => 'status_diperbarui',
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'title' => 'Status tiket diperbarui',
                    'message' => sprintf('Status tiket %s berubah menjadi %s', $ticket->ticket_number, ucfirst($status)),
                    'url' => route('tickets.show', $ticket),
                    'by_user_id' => $assignedTo ?? $technicians->random()->id,
                    'by_user_name' => $assignedTo ? User::find($assignedTo)->name : $technicians->random()->name,
                ]));
            }

            // Create response notification
            if ($i % 2 == 0 && $i <= 10) {
                $user->notify(new TicketActivityNotification([
                    'type' => 'tanggapan_baru',
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'title' => 'Tanggapan baru pada tiket',
                    'message' => sprintf('Tanggapan baru ditambahkan pada tiket %s', $ticket->ticket_number),
                    'url' => route('tickets.show', $ticket),
                    'by_user_id' => $assignedTo ?? $technicians->random()->id,
                    'by_user_name' => $assignedTo ? User::find($assignedTo)->name : $technicians->random()->name,
                ]));
            }
        }

        $this->command->info('✓ Created 20 tickets with notifications');
    }

    private function getTicketTitle($index): string
    {
        $titles = [
            'Laptop tidak bisa nyala',
            'Printer tidak bisa print',
            'Internet lemot',
            'Email tidak bisa dikirim',
            'Komputer restart sendiri',
            'Monitor tidak tampil',
            'Keyboard rusak',
            'Mouse tidak terdeteksi',
            'Aplikasi error',
            'VPN tidak bisa connect',
            'Wifi tidak stabil',
            'File tidak bisa dibuka',
            'Software crash terus',
            'Lupa password',
            'Akses ditolak',
            'Harddisk penuh',
            'Suara laptop tidak keluar',
            'Webcam tidak berfungsi',
            'USB tidak terbaca',
            'Aplikasi lemot',
        ];

        return $titles[$index - 1] ?? 'Issue #' . $index;
    }

    private function getTicketDescription($index): string
    {
        $descriptions = [
            'Laptop saya tiba-tiba mati dan tidak bisa dinyalakan kembali. Sudah dicoba charge tapi tetap tidak bisa.',
            'Printer di ruangan tidak bisa print dokumen. Sudah dicoba restart tapi masih error.',
            'Koneksi internet di kantor sangat lambat, susah buka website dan download file.',
            'Email saya tidak bisa mengirim attachment. Muncul error message terus.',
            'Komputer saya sering restart sendiri tanpa peringatan, sangat mengganggu pekerjaan.',
            'Monitor komputer saya tidak menampilkan apa-apa, hanya layar hitam saja.',
            'Beberapa tombol keyboard tidak berfungsi, terutama huruf A, S, D.',
            'Mouse USB saya tidak terdeteksi di komputer, sudah coba ganti port tetap tidak bisa.',
            'Aplikasi CRM selalu error saat membuka data customer.',
            'VPN kantor tidak bisa connect, muncul pesan connection timeout.',
            'Koneksi wifi sering putus-putus, harus reconnect berkali-kali.',
            'File Excel saya tidak bisa dibuka, muncul pesan file corrupt.',
            'Software accounting sering crash saat sedang input data.',
            'Saya lupa password email kantor, mohon direset.',
            'Tidak bisa akses folder shared di server, muncul access denied.',
            'Harddisk laptop hampir penuh, perlu diperbesar kapasitasnya.',
            'Speaker laptop tidak mengeluarkan suara sama sekali.',
            'Webcam tidak terdeteksi saat meeting online.',
            'Flashdisk tidak terbaca di komputer, padahal di komputer lain bisa.',
            'Aplikasi ERP sangat lambat saat membuka modul inventory.',
        ];

        return $descriptions[$index - 1] ?? 'Deskripsi issue #' . $index;
    }
}