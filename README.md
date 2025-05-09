🏛️ Sistem Informasi Peminjaman Arsip Pertanahan (Land Archive Loan System)
PHP Version
MySQL Version
Bootstrap Version
License

Sistem Informasi Peminjaman Arsip Pertanahan adalah solusi digital komprehensif untuk mengelola arsip-arsip penting pertanahan seperti Buku Tanah, Warkah, dan Surat Ukur. Aplikasi web ini dirancang untuk meningkatkan efisiensi, transparansi, dan akuntabilitas dalam proses peminjaman arsip di instansi pertanahan.

🌟 Fitur Utama
🔐 Sistem Autentikasi
Multi-level login (Admin, Petugas, Peminjam)

Verifikasi email untuk pendaftaran akun baru

Reset password melalui email

Manajemen session yang aman

Enkripsi password dengan bcrypt

📂 Manajemen Arsip Digital
CRUD lengkap untuk:

Buku Tanah (No. Hak, Jenis Hak, Luas Tanah, dll)

Warkah (No. Warkah, Tahun, Lokasi Penyimpanan)

Surat Ukur (No. Surat Ukur, Tanggal Pembuatan)

Pencarian arsip dengan berbagai filter

Preview dokumen digital (PDF/Image)

Sistem tagging dan kategorisasi

📝 Sistem Peminjaman
Formulir peminjaman online

Tracking status peminjaman (Diproses, Disetujui, Ditolak, Dikembalikan)

Riwayat peminjaman lengkap

Notifikasi otomatis

Sistem pengembalian dengan konfirmasi

📊 Dashboard & Analitik
Statistik real-time:

Total arsip

Peminjaman aktif

Peminjaman per bulan

Visualisasi data dengan Chart.js

Export laporan (PDF, Excel)

Audit log untuk aktivitas sistem

⚙️ Admin Panel
Manajemen pengguna

Konfigurasi sistem

Backup database

Monitoring aktivitas

🛠 Teknologi Stack
Backend
PHP 8.0+ - Bahasa pemrograman utama

MySQL/MariaDB - Sistem manajemen database

Apache/Nginx - Web server

Frontend
Bootstrap 5 - Framework CSS

Chart.js - Visualisasi data

DataTables - Tabel interaktif

Font Awesome - Ikon modern

jQuery - Library JavaScript

Tools & Libraries
Composer - Dependency management

PHPMailer - Sistem email

FPDF - Generate PDF

PHPExcel - Export ke Excel (opsional)

📦 Prasyarat Sistem
Sebelum menginstal, pastikan server Anda memenuhi persyaratan berikut:

PHP 8.0 atau lebih baru

MySQL 5.7+/MariaDB 10.2+

Ekstensi PHP: PDO, mbstring, GD, OpenSSL

Web server (Apache/Nginx) dengan mod_rewrite diaktifkan

Composer (untuk dependency management)

🚀 Panduan Instalasi
1. Clone Repository
bash
git clone https://github.com/username/sistem-peminjaman-arsip-pertanahan.git
cd sistem-peminjaman-arsip-pertanahan
2. Install Dependencies
bash
composer install
3. Setup Database
Buat database MySQL baru

Import file SQL yang tersedia di database/dump.sql

Atau jalankan migrasi (jika menggunakan sistem migrasi)

4. Konfigurasi Aplikasi
Salin file .env.example menjadi .env dan sesuaikan:

env
DB_HOST=localhost
DB_NAME=nama_database
DB_USER=username_db
DB_PASS=password_db

APP_URL=http://localhost/sistem-arsip
EMAIL_HOST=smtp.example.com
EMAIL_USER=admin@example.com
EMAIL_PASS=password_email
5. Jalankan Aplikasi
Letakkan folder project di direktori web server (htdocs/public_html)

Akses melalui browser:

http://localhost/sistem-arsip
6. Akun Default
Admin:

Email: admin@example.com

Password: Admin123

Petugas:

Email: petugas@example.com

Password: Petugas123

📚 Dokumentasi Penggunaan
Untuk Admin
Login dengan akun admin

Akses Admin Panel melalui menu navigasi

Kelola pengguna, arsip, dan konfigurasi sistem

Pantau statistik dan generate laporan

Untuk Petugas
Login dengan akun petugas

Verifikasi peminjaman arsip

Update status peminjaman

Kelola arsip pertanahan

Untuk Peminjam
Daftar akun baru atau login

Ajukan peminjaman arsip melalui formulir

Pantau status peminjaman

Lihat riwayat peminjaman

🛡️ Keamanan Sistem
Proteksi CSRF

Sanitasi input data

Pembatasan akses berbasis role

Enkripsi data sensitif

Log aktivitas pengguna

Backup otomatis mingguan


✉️ Kontak
Pengembang: Aryamukti Satria Hendrayana

Email: aryamuktisatria@gmail.com
