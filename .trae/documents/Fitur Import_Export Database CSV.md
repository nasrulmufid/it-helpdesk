## Implementasi Fitur Import/Export Database (CSV)

Saya akan menambahkan fitur untuk melakukan import dan export data **Users**, **Categories**, dan **Tickets** dalam format CSV.

### Technical Implementation:

1. **Controller Baru**:
   - Membuat `DataMigrationController` untuk menangani logika:
     - `exportUsers()`, `importUsers()`
     - `exportCategories()`, `importCategories()`
     - `exportTickets()`, `importTickets()`
     - `downloadTemplate()`

2. **Routes**:
   - Menambahkan rute admin baru di `routes/web.php` untuk akses import/export.

3. **Views**:
   - Membuat halaman `resources/views/admin/import-export.blade.php` dengan UI modern (Tailwind CSS) untuk manajemen data.
   - Menambahkan menu "Migrasi Data" pada sidebar admin di `resources/views/layouts/app.blade.php`.

4. **CSV Templates**:
   - **Users**: `name,email,password,role,phone,department`
   - **Categories**: `name,description,icon,is_active`
   - **Tickets**: `title,description,user_email,category_name,priority,status,assigned_to_email`

5. **Security & Validation**:
   - Proteksi middleware `admin`.
   - Validasi data CSV (unique email, existing categories, valid roles/priorities).
   - Hashing password otomatis untuk import User.

Apakah rencana ini sudah sesuai dengan keinginan Anda?