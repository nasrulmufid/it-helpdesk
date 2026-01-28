docker-compose build --no-cache
docker-compose up -d
docker exec -it <app_container_name> bash

# IT Help Desk — Presentasi Lengkap

> Ringkasan lengkap untuk demo / presentasi proyek Help Desk berbasis Laravel 11.

---

## Slide 1 — Judul

IT Help Desk — Sistem Tiket Support Internal

Presenter: [Nama Anda]

Tanggal: 22 Januari 2026

Ringkasan singkat: aplikasi helpdesk untuk membuat, menugaskan, dan menutup tiket bantuan IT dengan fitur notifikasi dan pelaporan.

---

## Slide 2 — Tujuan & Masalah yang Diselesaikan

- Tujuan utama:
  - Menyediakan proses pelaporan masalah IT yang terstruktur.
  - Memudahkan penugasan dan pelacakan penyelesaian masalah.
  - Memberikan laporan dan notifikasi kepada pemangku kepentingan.

- Masalah nyata yang ditangani:
  - Permintaan support yang tersebar dan tidak terdokumentasi.
  - Sulitnya memonitor status dan SLA per tiket.
  - Komunikasi antara pengguna dan teknisi yang kurang terkoordinasi.

---

## Slide 3 — Arsitektur & Stack

- Backend: Laravel 11 (PHP 8.2)
- Frontend: Blade templating + Tailwind CSS + Vite
- Database: SQLite untuk dev/demo (migrations & seeders tersedia), rekomendasi MySQL/Postgres untuk produksi
- Containerization: Docker (php:8.2-apache) + docker-compose
- Notifikasi: Laravel Notifications (database)
- PDF Export: barryvdh/laravel-dompdf

Catatan: repo termasuk `Dockerfile`, `docker-compose.yml`, seeders dan migration sehingga bisa langsung di-run untuk demo.

---

## Slide 4 — Peran (Roles) & Hak Akses

- Roles yang tersedia:
  - `admin` — akses penuh, manajemen user & kategori, laporan
  - `general_manager` — melihat laporan & dashboard (serupa admin untuk reporting)
  - `technician` — menerima tugas, merespon tiket
  - `user` — buat tiket, lihat status tiket sendiri

- Mekanisme kontrol akses:
  - Middleware `auth`, `admin`, `report.access` digunakan pada routes
  - Helper role-check di model `User` (isAdmin, isTechnician, isUser, isGeneralManager)

---

## Slide 5 — Alur Pengguna (User Flow)

1. User mendaftar / login.
2. User membuat tiket (title, description, category, priority).
3. Sistem membuat ticket_number unik, menyimpan record, dan mengirim notifikasi ke admin/GM/tech.
4. Admin/technician melihat daftar tiket, menugaskan teknisi, atau mengubah status.
5. Technician menambahkan tanggapan (internal atau public).
6. User mendapat notifikasi ketika ada update/tanggapan.
7. Admin/GM dapat melihat laporan agregat dan mengekspor PDF.

---

## Slide 6 — Routes Utama (dari `routes/web.php`)

- Guest routes (middleware `guest`):
  - GET `/`, GET `/login` -> `AuthController@showLogin`
  - POST `/login` -> `AuthController@login`
  - GET `/register`, POST `/register` -> registration

- Authenticated routes (middleware `auth`):
  - POST `/logout` -> `AuthController@logout`
  - GET `/dashboard` -> `DashboardController@index`

- Tickets (`prefix('tickets')`):
  - GET `/tickets` -> `TicketController@index`
  - GET `/tickets/create` -> `TicketController@create`
  - POST `/tickets` -> `TicketController@store`
  - GET `/tickets/{ticket}` -> `TicketController@show`
  - PUT `/tickets/{ticket}` -> `TicketController@update`
  - POST `/tickets/{ticket}/responses` -> `TicketController@addResponse`
  - POST `/tickets/{ticket}/assign` -> `TicketController@assign`
  - POST `/tickets/{ticket}/assign-self` -> `TicketController@assignToSelf`

- Notifications (`prefix('notifications')`):
  - GET `/notifications` -> `NotificationController@index`
  - POST `/notifications/read-all` -> `NotificationController@markAllRead`
  - POST `/notifications/{notification}/read` -> `NotificationController@markAsRead`

- Reports (`middleware('report.access')`):
  - GET `/reports` -> `ReportController@index`
  - GET `/reports/ticket-status` -> `ReportController@ticketStatus`
  - GET `/reports/category-performance` -> `ReportController@categoryPerformance`
  - GET `/reports/technician-performance` -> `ReportController@technicianPerformance`
  - GET `/reports/user-activity` -> `ReportController@userActivity`
  - GET `/reports/export-pdf` -> `ReportController@exportPdf`

- Admin (`middleware('admin')`, prefix `admin`):
  - User management routes (index, create, store, edit, update, destroy)
  - Category management routes (index, create, store, edit, update, destroy)

---

## Slide 7 — Controller: Rincian & Fungsi (lengkap)

Catatan: berikut merit tiap controller beserta method utama dan penjelasan singkat.

1) `AuthController`
  - `showLogin()` — menampilkan halaman login (`resources/views/auth/login.blade.php`).
  - `login(Request $request)` — validasi kredensial, login via `Auth::attempt`, regenerate session, redirect ke dashboard.
  - `showRegister()` — menampilkan form registrasi.
  - `register(Request $request)` — validasi input, hashing password, create user (role `user`), login otomatis, redirect ke dashboard.
  - `logout(Request $request)` — logout, invalidate session, regenerate token.

2) `DashboardController`
  - `index()` — memilih dashboard berdasarkan role (admin/GM -> adminDashboard, technician -> technicianDashboard, user -> userDashboard).
  - `adminDashboard()` — kumpulkan statistik global (total tickets per status, total users/technicians), recent tickets, tickets by category; menampilkan `dashboard.admin`.
  - `technicianDashboard()` — statistik khusus teknisi (assigned, in-progress, resolved), assigned/unassigned lists; menampilkan `dashboard.technician`.
  - `userDashboard()` — statistik user hanya untuk tiket milik user, list tiket user, kategori aktif.

3) `TicketController`
  - `index(Request $request)` — daftar tiket dengan kemampuan filter (`status`, `priority`, `assigned_to`) dan role-aware filtering (user hanya lihat tiket sendiri; technician lihat assigned atau unassigned).
  - `create()` — ambil kategori aktif dan render form create (`tickets.create`).
  - `store(Request $request)` — validasi input, generate `ticket_number` (helper `Ticket::generateTicketNumber()`), create record, kirim notifikasi ke admins/GM/technicians.
  - `show(Ticket $ticket)` — otorisasi, load relasi (`user`, `category`, `assignedTo`, `responses`, `attachments`), render `tickets.show`.
  - `update(Request $request, Ticket $ticket)` — admin/technician only: update fields (status, priority, assigned_to), set resolved_at/closed_at bila relevan; kirim notifikasi bila status/assignment berubah.
  - `addResponse(Request $request, Ticket $ticket)` — create `TicketResponse`, respect `is_internal` (hanya admin/technician dapat internal notes), kirim notifikasi sesuai tipe (internal -> admins/techs; public -> ticket owner & assigned).
  - `assign(Request $request, Ticket $ticket)` — set assigned_to, kirim notifikasi.
  - `assignToSelf(Ticket $ticket)` — technician mengambil tiket sendiri (set assigned_to = auth user), kirim notifikasi.
  - `sendTicketNotification($recipients, string $type, Ticket $ticket, string $title, string $message)` — helper private untuk merapikan payload dan mengirim `Notification::send`.

4) `NotificationController`
  - `index()` — paginate notifikasi user (`notifications.index`).
  - `markAllRead()` — tandai semua unreadNotifications jadi read.
  - `markAsRead(DatabaseNotification $notification)` — tandai 1 notifikasi sebagai read setelah verifikasi owner.

5) `ReportController`
  - `index()` — hitung overview stats (total tickets, total users/technicians, avg response time) -> `reports.index`.
  - `ticketStatus(Request)` — kumpulkan tiket pada rentang tanggal, hitung count per status & priority -> `reports.ticket-status`.
  - `categoryPerformance(Request)` — hitung stats per kategori termasuk avg resolution time -> `reports.category-performance`.
  - `technicianPerformance(Request)` — hitung stats per teknisi (assigned/resolved/response count) -> `reports.technician-performance`.
  - `userActivity(Request)` — aktivitas user -> `reports.user-activity`.
  - `exportPdf(Request)` — kumpulkan data dan gunakan `Pdf::loadView` (dompdf) untuk generate PDF laporan.

6) `AdminController`
  - User management: `users()`, `createUser()`, `storeUser()`, `editUser(User $user)`, `updateUser(Request, User)`, `destroyUser(User)`.
  - Category management: `categories()`, `createCategory()`, `storeCategory()`, `editCategory(Category)`, `updateCategory(Request, Category)`, `destroyCategory(Category)` (cek ada tiket sebelum delete).

---

## Slide 8 — Models: Atribut & Relasi (detail)

1) `User` (App\Models\User)
  - fillable: `name`, `email`, `password`, `role`, `phone`, `department`.
  - relasi: `tickets()` (hasMany Ticket), `assignedTickets()` (hasMany Ticket dengan foreign key `assigned_to`), `ticketResponses()` (hasMany TicketResponse).
  - helper role checks: `isAdmin()`, `isGeneralManager()`, `isTechnician()`, `isUser()`.

2) `Ticket` (App\Models\Ticket)
  - fillable: `ticket_number`, `title`, `description`, `user_id`, `category_id`, `assigned_to`, `priority`, `status`, `resolved_at`, `closed_at`.
  - relasi: `user()`, `category()`, `assignedTo()`, `responses()`, `attachments()`.
  - scopes: `scopeOpen`, `scopeInProgress`, `scopeResolved`, `scopeClosed`, `scopeByPriority`.
  - helpers: `generateTicketNumber()`, accessor `priority_label`, `status_label`.

3) `TicketResponse` — `ticket()`, `user()`, fillable `ticket_id`, `user_id`, `message`, `is_internal`.

4) `TicketAttachment` — metadata file, helper `getFileSizeFormattedAttribute`.

5) `Category` — `tickets()` hasMany, `scopeActive()`.

---

## Slide 9 — Database Schema (migrations penting)

- `users`:
  - `id`, `name`, `email` (unique), `password`, `role` (enum: admin, technician, user, general_manager), `phone`, `department`, `remember_token`, `timestamps`.

- `tickets`:
  - `id`, `ticket_number` (unique), `title`, `description`, `user_id` (foreign -> users, cascade), `category_id` (foreign -> categories, cascade), `assigned_to` (foreign -> users, nullable, onDelete set null), `priority` enum, `status` enum, `resolved_at`, `closed_at`, `timestamps`.

- `ticket_responses`:
  - `id`, `ticket_id`, `user_id`, `message`, `is_internal` (boolean default false), timestamps.

- `ticket_attachments`:
  - `id`, `ticket_id`, `file_name`, `file_path`, `file_type`, `file_size`, timestamps.

- `categories`:
  - `id`, `name`, `slug` (unique), `description`, `icon`, `is_active`, timestamps.

- `notifications`:
  - `uuid id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, timestamps.

Catatan: migration files ada di `database/migrations/` — lihat 2024_01_01_000004_create_tickets_table.php untuk detail kolom dan FK.

---

## Slide 10 — Seeders & Data Demo (apa yang dihasilkan)

- `CategorySeeder` — membuat kategori default seperti Hardware, Software, Network, Email, Database, Security, Account, Other.
- `UserSeeder` — membuat beberapa akun demo:
  - Admin: `admin@helpdesk.com` / `password`
  - General Manager: `gm@helpdesk.com` / `password`
  - Technicians: `tech1@helpdesk.com`, `tech2@helpdesk.com` (/ `password`)
  - Demo users: `user@helpdesk.com`, `john@helpdesk.com`, `jane@helpdesk.com`, `bob@helpdesk.com` (/ `password`)
- `TicketSeeder` — membuat 20 tiket sample, menambahkan tanggapan pada beberapa tiket, serta membuat notifikasi untuk admin/teknisi.

Manfaat: demo langsung menampilkan daftar tiket, dashboard terisi, dan notifikasi yang dapat ditunjukkan saat presentasi.

---

## Slide 11 — Views (halaman & tujuan)

- `resources/views/layouts/app.blade.php` — layout utama (sidebar, header, dropdown notifikasi, area konten)
- Auth: `auth/login.blade.php`, `auth/register.blade.php`
- Dashboard: `dashboard/admin.blade.php`, `dashboard/technician.blade.php`, `dashboard/user.blade.php`
- Tickets: `tickets/index.blade.php` (listing), `tickets/create.blade.php` (form buat), `tickets/show.blade.php` (detail & responses)
- Reports: views di folder `reports/` (index dan detail pages) + `reports/pdf/complete.blade.php` untuk export
- Notifications: `notifications/index.blade.php`
- Admin: `admin/users/*.blade.php`, `admin/categories/*.blade.php`

UI: menggunakan Tailwind untuk styling dan sedikit JS untuk toggles (sidebar, dropdown).

---

## Slide 12 — Notifikasi & Mekanisme

- Class: `App\Notifications\TicketActivityNotification`
  - `via()` -> `['database']` (disimpan di tabel `notifications`)
  - `toArray()` -> payload: `type`, `title`, `message`, `ticket_id`, `ticket_number`, `url`, `by_user_id`, `by_user_name`.

- Notifikasi dikirim di banyak event: ticket creation, assignment, status change, responses.
- Notifikasi ditampilkan di header (7 hari terakhir) dan halaman notifikasi.

---

## Slide 13 — Alur Demo (step-by-step untuk live demo)

1. Build & jalankan container (lihat perintah di slide berikut).
2. Jalankan migrasi & seeder: `php artisan migrate:fresh --seed --force`.
3. Login sebagai `admin@helpdesk.com` / `password`:
   - Tunjukkan dashboard admin (statistik, recent tickets).
   - Masuk ke Admin -> Users -> tunjukkan user list & fitur CRUD.
4. Buka Tickets -> pilih tiket sample -> assign ke `tech1@helpdesk.com` -> tunjukkan notifikasi di header.
5. Login sebagai `tech1@helpdesk.com` -> lihat ticket assigned -> tambahkan response internal & public.
6. Kembali ke admin -> buka Reports -> pilih rentang -> export PDF -> tunjukkan download.

---

## Slide 14 — Perintah & Cara Menjalankan (PowerShell)

Catatan: sesuaikan `docker-compose.yml` bila port berbeda. Contoh perintah (PowerShell):

```powershell
# (1) Build image dan jalankan container
docker-compose build --no-cache
docker-compose up -d

# (2) Masuk ke container app (jika perlu)
docker exec -it it-help-desk-app bash

# (3) Jalankan migrasi + seeder (di dalam container atau menggunakan exec)
php artisan migrate:fresh --seed --force

# (4) Jika ada perubahan asset (dev)
npm install
npm run build # atau npm run dev untuk hot reload
```

URL demo biasanya: `http://localhost/` (atau port yang didefinisikan di file compose).

---

## Slide 15 — Hal-hal Teknis & Rekomendasi Produksi

- Database: pindahkan dari SQLite ke MySQL/Postgres untuk concurrency & reliability; setup backups & replication.
- Docker: gunakan multi-stage build untuk menghapus dev dependencies dari image produksi.
- Storage: simpan attachments di object storage (S3) bukan di filesystem container; tambahkan validasi size/type.
- Queue: gunakan queue worker (Redis/Beanstalkd) untuk mengirim email/notifications agar tidak blocking request.
- Security: pisahkan env untuk produksi, set `APP_ENV=production`, `APP_DEBUG=false`, dan pastikan APP_KEY aman.
- Logging: setup log rotate & central logging (ELK / Loki) untuk observability.

---

## Slide 16 — Edge Cases & Testing yang Perlu Ditunjukkan

- Periksa otorisasi: user tidak boleh melihat ticket orang lain.
- Periksa validasi input (title, description, category existance).
- Attachment upload size/type validation.
- Concurrency: dua teknisi assign bersamaan -> cek last-writer wins atau locking.

Tambahkan 1–2 unit/integration tests untuk endpoint kritis (create ticket, add response, assign).

---

## Slide 17 — Next Steps & Peta Fitur

- Integrasi email + push notifications.
- Implementasi SLA & target waktu penyelesaian pada tiap tiket.
- Audit log & change history per tiket.
- SSO / LDAP untuk enterprise authentication.

---

## Slide 18 — Q&A dan Kontak

Terima kasih — pertanyaan? Jika butuh, saya bisa:

- Mengonversi file ini ke PPTX (PowerPoint) yang siap presentasi.
- Menambahkan screenshot dari halaman utama (`tickets.index`) dan `tickets.show`.
- Menyusun skrip demo singkat (naskah presenter) untuk tiap langkah demo.

---

_File ini dihasilkan otomatis dan diperluas sesuai permintaan. Mau saya konversi ke PPTX sekarang?_ 