# 🔑 SiKunci Lab — Sistem Peminjaman Kunci Laboratorium Sekolah

Aplikasi web berbasis **Laravel 11** untuk mengelola peminjaman kunci laboratorium sekolah secara digital. Menggantikan buku manual dengan sistem terkomputerisasi yang efisien dan tertelusuri.

---

## 📋 Fitur Utama

### Role & Akses
| Role | Akses |
|---|---|
| **Admin** | CRUD user, CRUD lab, semua laporan, reset poin, blokir/unblokir murid |
| **Pengawas** | Konfirmasi peminjaman, serah/terima kunci, catat pelanggaran |
| **Murid** | Ajukan peminjaman, isi checklist, upload foto, lihat riwayat |

### Fitur Per Role

**Admin:**
- Dashboard dengan statistik & grafik 7 hari
- CRUD user (admin, pengawas, murid)
- CRUD lab (8 lab tersedia)
- Laporan peminjaman (filter tanggal, lab, status)
- Laporan keterlambatan
- Laporan pelanggaran
- Reset poin & blokir/unblokir murid

**Pengawas:**
- Dashboard real-time (menunggu, berlangsung, checklist)
- Setujui / tolak pengajuan peminjaman
- Konfirmasi serah kunci ke murid
- Konfirmasi terima kunci dari murid
- Catat pelanggaran manual (keterlambatan, kerusakan, kebersihan, dll)

**Murid:**
- Dashboard dengan status peminjaman aktif
- Ajukan peminjaman lab dengan pilih jadwal
- Isi checklist kondisi ruangan (5 item)
- Upload foto kondisi ruangan
- Riwayat peminjaman lengkap

### Sistem Pelanggaran
- Poin diinput **manual** oleh Pengawas/Admin
- Murid **otomatis diblokir** jika total poin ≥ 5
- Admin dapat reset poin dan unblokir murid

---

## 🗃️ Data Lab

| Lab | Kode | Kapasitas |
|---|---|---|
| Lab ASJ | ASJ | 36 |
| Lab Bootcamp | BOOT | 30 |
| Lab FO | FO | 24 |
| Lab KJD | KJD | 36 |
| Lab AIJ | AIJ | 32 |
| Lab IoT | IOT | 20 |
| Lab Admin | ADM | 30 |
| Lab Desain | DES | 28 |

---

## 🚀 Instalasi di Server Sekolah (XAMPP)

### Prasyarat
- **XAMPP** (Apache + MySQL + PHP 8.2+) — Download di [apachefriends.org](https://www.apachefriends.org)
- **Composer** — Download di [getcomposer.org](https://getcomposer.org)
- **Git** (opsional) — Download di [git-scm.com](https://git-scm.com)

### Langkah Instalasi

**1. Siapkan XAMPP**
```
1. Install XAMPP
2. Buka XAMPP Control Panel
3. Start Apache dan MySQL
4. Buka browser → http://localhost/phpmyadmin
```

**2. Buat Database**
```sql
-- Di phpMyAdmin, jalankan query ini:
CREATE DATABASE lab_key_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**3. Copy Project**
```
Salin folder lab-key-system ke:
C:\xampp\htdocs\lab-key-system
```

**4. Install Dependencies**
```bash
# Buka Command Prompt, masuk ke folder project
cd C:\xampp\htdocs\lab-key-system

# Install dependencies PHP
composer install

# Jika composer tidak dikenali, gunakan path penuh:
# C:\xampp\php\php.exe composer.phar install
```

**5. Konfigurasi Environment**
```bash
# Copy file konfigurasi
copy .env.example .env

# Generate app key
php artisan key:generate
```

**6. Edit file .env**
```
# Buka file .env dengan Notepad, ubah bagian database:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lab_key_system
DB_USERNAME=root
DB_PASSWORD=          ← kosong jika XAMPP default
```

**7. Migrasi Database & Seeder**
```bash
# Buat tabel dan isi data awal
php artisan migrate --seed

# Atau jika ingin reset total:
php artisan migrate:fresh --seed
```

**8. Setup Storage (untuk upload foto)**
```bash
php artisan storage:link
```

**9. Jalankan Aplikasi**
```bash
php artisan serve
# Akses di: http://localhost:8000
```

---

## 🌐 Akses dari Komputer Lain di Jaringan

Agar bisa diakses dari komputer murid/guru lain:

**Opsi A — via PHP Artisan Serve:**
```bash
# Jalankan dengan bind ke semua IP
php artisan serve --host=0.0.0.0 --port=8000
# Akses dari komputer lain: http://IP_SERVER:8000
# Contoh: http://192.168.1.10:8000
```

**Opsi B — via Apache XAMPP (Rekomendasi Production):**
```
1. Buka C:\xampp\apache\conf\extra\httpd-vhosts.conf
2. Tambahkan virtual host:

<VirtualHost *:80>
    ServerName labkey.sekolah.local
    DocumentRoot "C:/xampp/htdocs/lab-key-system/public"
    <Directory "C:/xampp/htdocs/lab-key-system/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

3. Restart Apache
4. Akses: http://IP_SERVER atau http://labkey.sekolah.local
```

---

## 👤 Akun Default (Setelah Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@labsekolah.com | password |
| Pengawas | pengawas@labsekolah.com | password |
| Murid | ahmad@labsekolah.com | password |

> ⚠️ **Ganti password default segera setelah instalasi!**

---

## 📁 Struktur Direktori

```
lab-key-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── LabController.php
│   │   │   │   └── LaporanController.php
│   │   │   ├── Pengawas/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── KunciController.php
│   │   │   │   └── PelanggaranController.php
│   │   │   └── Murid/
│   │   │       ├── DashboardController.php
│   │   │       ├── PeminjamanController.php
│   │   │       └── ChecklistController.php
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php
│   │       └── CheckBlocked.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Lab.php
│   │   ├── Peminjaman.php
│   │   ├── PenyerahanKunci.php
│   │   ├── ChecklistPengembalian.php
│   │   └── Pelanggaran.php
│   └── Providers/AppServiceProvider.php
├── bootstrap/app.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/login.blade.php
│   ├── admin/
│   ├── pengawas/
│   └── murid/
├── routes/web.php
├── .env.example
└── composer.json
```

---

## 🔄 Alur Peminjaman

```
[Murid] Ajukan Peminjaman
        ↓
[Pengawas] Lihat di dashboard → Setujui / Tolak
        ↓ (jika disetujui)
[Pengawas] Serahkan Kunci (klik tombol "Serahkan Kunci")
        ↓
[Murid] Menggunakan Lab
        ↓
[Murid] Isi Checklist Pengembalian + Upload Foto Ruangan
        ↓
[Pengawas] Terima Kunci Kembali (klik "Terima Kunci")
        ↓ (opsional)
[Pengawas/Admin] Catat Pelanggaran Manual jika ada masalah
        ↓
[Sistem] Cek poin ≥ 5 → Blokir otomatis jika ya
```

---

## 🛠️ Perintah Artisan Berguna

```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed

# Bersihkan cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Lihat semua routes
php artisan route:list

# Masuk ke tinker (REPL)
php artisan tinker
```

---

## 📊 Spesifikasi Teknis

| Komponen | Versi |
|---|---|
| PHP | 8.2+ |
| Laravel | 11.x |
| MySQL | 8.x (via XAMPP) |
| Bootstrap | 5.3 |
| Chart.js | 4.4 |
| Bootstrap Icons | 1.11 |

---

## 📝 Catatan Pengembangan

- Foto checklist disimpan di `storage/app/public/checklist-photos/`
- Session driver menggunakan `database` (perlu tabel sessions)
- Timezone aplikasi: `Asia/Jakarta`
- Locale: `id` (Indonesia)

---

© 2024 SiKunci Lab — Sistem Peminjaman Kunci Laboratorium Sekolah
