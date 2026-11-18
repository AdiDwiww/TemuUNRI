# TemuUNRI — Web Lost and Found Universitas Riau

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-%5E8.3-777BB4?style=flat&logo=php)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=flat&logo=vite)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=flat&logo=mysql)](https://www.mysql.com)
[![Tests](https://img.shields.io/badge/Tests-132%20Passed-success?style=flat&logo=githubactions)](tests)

---

## 1. Informasi Aplikasi

**TemuUNRI** adalah aplikasi web *Lost and Found* yang dirancang khusus untuk memfasilitasi pelaporan barang hilang dan pengumuman penemuan barang di lingkungan kampus **Universitas Riau (UNRI)**.

### Latar Belakang & Tujuan
Kehilangan atau tercecernya barang milik civitas akademika di area kampus (ruang kelas, laboratorium, perpustakaan, tempat ibadah, maupun fasilitas umum kampus lainnya) sering kali hanya diumumkan melalui grup pesan singkat atau media sosial yang tidak terpusat, cepat tertimbun, dan sulit dilacak. 

TemuUNRI hadir untuk:
1. Menyediakan wadah terpadu dan terstruktur dalam mendata setiap barang hilang (*lost*) maupun barang ditemukan (*found*).
2. Memfasilitasi mediasi dan verifikasi klaim kepemilikan barang secara transparan dan akuntabel.
3. Menyediakan jalur komunikasi privat (in-app chat) antara pelapor dan penemu tanpa harus langsung mengekspos nomor kontak pribadi.
4. Membangun ekosistem kampus Universitas Riau yang jujur, amanah, dan saling peduli.

### Sasaran Pengguna
- **Mahasiswa** di seluruh fakultas Universitas Riau.
- **Dosen & Tenaga Kependidikan**.
- **Staf Keamanan & Pengelola Fasilitas Kampus**.
- **Administrator Sistem** yang bertanggung jawab atas moderasi konten dan validasi klaim.

---

## 2. Fitur Utama Aplikasi

Seluruh fitur berikut diimplementasikan secara nyata pada kode sumber TemuUNRI:

### A. Autentikasi & Manajemen Pengguna
- **Pendaftaran Akun Terstruktur**: Formulir registrasi civitas akademika dengan kelengkapan data: Nama Lengkap, NIM, Fakultas, Program Studi, Nomor HP (opsional), Email UNRI, dan Kata Sandi.
- **Desain Autentikasi Split-Screen**: Antarmuka Login dan Register modern dengan kolom formulir di sisi kiri dan panel grafis abstrak Navy/Slate di sisi kanan.
- **Show/Hide Password**: Kemudahan mengecek kata sandi secara interaktif tanpa reload halaman.
- **Profil Pengguna**:
  - Pembaruan nama, email, fakultas, program studi, dan nomor HP.
  - Pembaruan NIM secara dinamis dengan validasi format dan keunikan database.
  - Penggantian kata sandi dengan validasi kata sandi lama.
  - Upload dan hapus foto profil pengguna (`public/profile_photos`).
  - Hapus akun mandiri dengan konfirmasi keamanan kata sandi.

### B. Pelaporan Barang Hilang & Ditemukan
- **Klasifikasi Tipe Laporan**: Pemisahan jelas antara barang kehilangan (`tipe = 'hilang'`) dan barang temuan (`tipe = 'ditemukan'`).
- **Data Laporan Komprehensif**: Menampung atribut judul barang, kategori, lokasi spesifik di lingkungan UNRI, tanggal kejadian, deskripsi detail tanda-tanda khusus, dan foto barang.
- **Siklus Hidup Status Laporan**:
  - `pending`: Laporan baru menunggu peninjauan dan moderasi oleh admin.
  - `proses`: Laporan telah disetujui admin dan aktif tampil di beranda publik.
  - `selesai`: Barang telah berhasil ditemukan/dikembalikan ke pemilik sahnya.
  - `ditolak`: Laporan ditolak oleh admin karena melanggar ketentuan atau tidak valid.
- **Tandai Barang Ditemukan**: Pemilik laporan kehilangan dapat menandai laporannya selesai secara mandiri setelah barang berhasil ditemukan kembali, yang secara otomatis mengarsipkan laporan ke tab riwayat selesai.
- **Hapus / Takedown Postingan**: Pengguna dapat menghapus laporannya sendiri, dan admin memiliki kewenangan untuk melakukan *takedown* postingan yang melanggar aturan.

### C. Pemotongan Foto Interaktif (Image Cropper Rasio 4:5)
- Integrasi library **Cropper.js** pada form tambah laporan (`reports/create.blade.php`) dan edit laporan (`reports/edit.blade.php`).
- Rasio pemotongan dikunci pada **4:5 portrait** (format feed Instagram) agar kartu laporan pada beranda tertata seragam dan estetis.
- Fitur interaktif: zoom in/out, rotasi, geser area foto (drag), tombol reset crop, dan konfirmasi hasil.
- Konversi gambar cropped berbasis canvas Blob menjadi file yang dikirimkan secara asynchronous ke server Laravel tanpa merusak alur validasi backend.

### D. Pencarian & Filter Laporan Real-Time (AJAX)
- **Live Search**: Pencarian barang berdasarkan judul, deskripsi, atau lokasi kampus secara langsung dengan mekanisme debounce 300–500 ms tanpa reload halaman.
- **Filter Berdasarkan Kategori**: Pemilahan barang berdasarkan kategori elektronik, dokumen berharga, perlengkapan kuliah, aksesoris, dll.
- **Tab Navigasi Beranda**: Pemisahan tab *Barang Hilang*, *Barang Temuan*, dan *Riwayat Selesai*.

### E. Pengajuan & Verifikasi Klaim Barang
- Pengguna yang merasa mengenali barang temuan miliknya dapat mengajukan klaim dengan memberikan deskripsi tanda khusus serta melampirkan bukti foto kepemilikan.
- Penemu barang dan admin dapat meninjau klaim yang masuk melalui halaman khusus klaim masuk (`/claims/incoming`).
- Opsi keputusan klaim: *Setujui (Approve)* atau *Tolak (Reject)* dengan konfirmasi validasi.

### F. Komunikasi Privat Antar Pengguna (In-App Chat via AJAX Polling)
- Ruang obrolan terisolasi (`conversations` dan `messages`) antara pelapor/penemu dan pengklaim barang.
- Pengiriman pesan instan tanpa reload halaman menggunakan JavaScript **Fetch API**.
- Polling otomatis berkala (setiap 3 detik) untuk mengambil pesan baru secara asynchronous.
- Autentikasi sesi dan proteksi CSRF token terintegrasi penuh.

### G. Dashboard & Moderasi Administrator
- Dilindungi middleware keamanan ganda: `auth` dan `admin` (Role-Based Access Control).
- **Statistik Sistem**: Ringkasan jumlah total laporan, laporan pending, laporan aktif (proses), laporan selesai, dan laporan ditolak.
- **Moderasi Satu Klik**: Tombol *Setujui* atau *Tolak* pada setiap laporan yang diajukan.
- **Moderasi Beranda**: Fitur *Takedown* postingan bermasalah langsung dari panel admin.
- **Verifikasi Klaim Admin**: Persetujuan dan penolakan klaim barang berharga.
- **Ekspor Data Laporan (CSV)**: Fitur ekspor seluruh rekapitulasi data laporan ke dalam format file `.csv` yang dilengkapi UTF-8 BOM agar kompatibel langsung dengan Microsoft Excel.

---

## 3. Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan arsitektur modern berbasis Laravel dan JavaScript vanilla:

| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Framework Backend** | [Laravel 13.x](https://laravel.com) | Framework PHP modern dengan arsitektur MVC, Eloquent ORM, dan Route Model Binding |
| **Bahasa Pemrograman** | [PHP ^8.3](https://www.php.net) | Mendukung tipe data ketat, constructor property promotion, dan performa tinggi |
| **Basis Data** | [MySQL](https://www.mysql.com) | Relational Database Management System (RDBMS) untuk relasi multi-tabel |
| **Template Engine** | [Blade Template](https://laravel.com/docs/blade) | Komponen UI modular, layouting dinamis, dan proteksi XSS otomatis |
| **Styling & CSS** | [Tailwind CSS v3](https://tailwindcss.com) | Utility-first CSS framework dengan kustomisasi palet Navy/Slate TemuUNRI |
| **Interaktivitas UI** | [Alpine.js v3](https://alpinejs.dev) | Declarative JavaScript micro-framework untuk modal, dropdown, dan toggle password |
| **Manipulasi Gambar** | [Cropper.js](https://fengyuanchen.github.io/cropperjs/) | Library manipulasi Canvas HTML5 untuk penguncian rasio foto 4:5 |
| **Asynchronous (AJAX)**| JavaScript [Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API) | Komunikasi HTTP asynchronous tanpa reload halaman dan pertukaran JSON |
| **Build Tool** | [Vite 7.x](https://vitejs.dev) | Modern frontend bundler untuk kompilasi super cepat aset CSS dan JS |
| **Package Manager** | Composer & NPM | Pengelolaan dependensi backend PHP dan frontend Node.js |
| **Testing Suite** | [PHPUnit 12.x](https://phpunit.de) | 132 automated tests (Unit & Feature) mencakup seluruh skenario bisnis |
| **Version Control** | Git & GitHub | Pelacakan riwayat commit dan kolaborasi tim |

---

## 4. Struktur Direktori Proyek

Ringkasan struktur direktori utama pada proyek TemuUNRI:

```text
TemuUNRI/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminDashboardController.php  # Controller moderasi admin & ekspor CSV
│   │   │   ├── Auth/                             # Controller registrasi, login, & password
│   │   │   ├── ClaimController.php               # Alur pengajuan & verifikasi klaim
│   │   │   ├── ConversationController.php        # Inisiasi percakapan in-app chat
│   │   │   ├── DashboardController.php           # Dashboard pribadi pengguna
│   │   │   ├── MessageController.php             # Endpoint AJAX pengiriman & polling chat
│   │   │   ├── ProfileController.php             # Edit profil, foto profil, & NIM
│   │   │   └── ReportController.php              # CRUD laporan & aksi tandai ditemukan
│   │   └── Middleware/
│   │       └── EnsureUserIsAdmin.php             # Proteksi akses RBAC admin
│   └── Models/
│       ├── User.php                              # Model pengguna & relasi peran
│       ├── Laporan.php                           # Model laporan barang hilang/temuan
│       ├── Category.php                          # Model kategori barang
│       ├── Claim.php                             # Model klaim kepemilikan barang
│       ├── Conversation.php                      # Model ruang obrolan
│       └── Message.php                           # Model pesan chat
├── bootstrap/
│   ├── app.php                                   # Konfigurasi aplikasi & routing Laravel 13
│   └── providers.php
├── config/                                       # Konfigurasi database, auth, filesystems, dll.
├── database/
│   ├── factories/                                # Model factories untuk pembuatan data dummy test
│   ├── migrations/                               # 10 file migrasi database terstruktur
│   └── seeders/
│       ├── AdminSeeder.php                       # Seeder akun admin pengujian lokal
│       └── DatabaseSeeder.php                    # Seeder basis data utama
├── public/
│   ├── build/                                    # Aset terkompilasi Vite (CSS, JS, manifest)
│   ├── js/
│   │   └── image-cropper.js                      # Logika Cropper.js rasio 4:5
│   └── storage/                                  # Symbolic link ke storage/app/public
├── resources/
│   ├── css/
│   │   └── app.css                               # Definisi tema Tailwind CSS kustom
│   ├── js/
│   │   ├── app.js                                # Entry point JavaScript & Alpine.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/                                # Tampilan panel dashboard admin
│       ├── auth/                                 # Tampilan login, register, split-screen partials
│       ├── claims/                               # Tampilan daftar klaim & form klaim
│       ├── conversations/                        # Tampilan chatroom asynchronous
│       ├── layouts/                              # Master layout (app, guest, navigation)
│       ├── profile/                              # Tampilan manajemen profil pengguna
│       └── reports/                              # Tampilan beranda, detail, buat, & edit laporan
├── routes/
│   ├── web.php                                   # Definisi seluruh endpoint aplikasi & admin
│   ├── auth.php                                  # Endpoint autentikasi Breeze
│   └── console.php
├── storage/
│   ├── app/
│   │   └── public/                               # File upload foto laporan, profil, & klaim
│   └── logs/                                     # Log aplikasi (laravel.log)
└── tests/
    ├── Feature/                                  # 13 kelas feature test (131 pengujian)
    └── Unit/                                     # Unit testing logika model & helper
```

---

## 5. Panduan Instalasi dan Menjalankan Aplikasi

Ikuti panduan langkah demi langkah di bawah ini untuk memasang dan menjalankan TemuUNRI di komputer lokal:

### Prasyarat Sistem
- **PHP** versi 8.3 atau lebih baru (dengan ekstensi `pdo`, `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl`).
- **Composer** versi 2.x.
- **Node.js** versi 18.x / 20.x / 24.x LTS dan **NPM**.
- **MySQL Database Server** (melalui Laragon, XAMPP, atau Docker).
- **Git**.

---

### Langkah-Langkah Instalasi

#### 1. Clone Repository
Buka terminal dan unduh repositori proyek:
```bash
git clone https://github.com/username/TemuUNRI.git
cd TemuUNRI
```

#### 2. Install Dependensi PHP (Composer)
Pasang seluruh paket pustaka backend:
```bash
composer install
```

#### 3. Siapkan File Konfigurasi Environment (`.env`)
Salin berkas contoh konfigurasi lingkungan:
```bash
# Untuk Linux / macOS / Git Bash:
cp .env.example .env

# Untuk Windows PowerShell:
copy .env.example .env
```

#### 4. Konfigurasi Basis Data pada `.env`
Buka file `.env` menggunakan teks editor (misalnya VS Code) dan sesuaikan konfigurasi koneksi database MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temuunri
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan**: Pastikan database dengan nama `temuunri` sudah dibuat di MySQL server Anda (misal via phpMyAdmin atau perintah SQL `CREATE DATABASE temuunri;`).

#### 5. Generate Application Encryption Key
Buat kunci enkripsi unik aplikasi Laravel:
```bash
php artisan key:generate
```

#### 6. Jalankan Migrasi Basis Data & Seeder
Buat seluruh struktur tabel ke database:
```bash
php artisan migrate
```

Jalankan seeder untuk membuat akun Administrator pengujian:
```bash
php artisan db:seed --class=AdminSeeder
```

> **Kredensial Akun Admin Pengujian**:
> - **Email**: `admin@temuunri.test`
> - **Password**: `AdminTemu2024!`
> - **Role**: `admin`

#### 7. Buat Symbolic Link Storage
Tautkan folder penyimpanan publik agar foto laporan dan foto profil dapat diakses oleh browser:
```bash
php artisan storage:link
```

#### 8. Install Dependensi JavaScript (NPM)
Pasang seluruh dependensi frontend:
```bash
npm install
```

#### 9. Kompilasi Aset Frontend
Kompilasi aset CSS dan JavaScript:
```bash
# Untuk kebutuhan produksi / pengujian stabil:
npm run build

# Atau untuk mode hot-reload pengembangan:
npm run dev
```

---

## 6. Cara Menjalankan Aplikasi

### Menjalankan Server Aplikasi
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```
Aplikasi akan berjalan di alamat: **`http://127.0.0.1:8000`**

### Menjalankan Frontend Bersamaan (Opsi Pengembangan)
Jika Anda melakukan perubahan styling atau script frontend, buka terminal kedua dan jalankan Vite:
```bash
npm run dev
```

### Opsi Perintah Cepat (`composer run dev`)
Jika package `concurrently` terpasang, Anda dapat menjalankan server Laravel, antrean worker, dan Vite development secara simultan hanya dengan satu perintah:
```bash
composer run dev
```

---

## 7. Pengujian Otomatis (Automated Testing)

Proyek ini telah dilengkapi dengan cakupan pengujian otomatis (*automated feature & unit tests*) menggunakan PHPUnit untuk memastikan stabilitas seluruh fitur:

Untuk menjalankan seluruh test suite:
```bash
php artisan test
```

Untuk menjalankan pengujian fitur tertentu (contoh: autentikasi, klaim, atau laporan):
```bash
# Pengujian modul autentikasi
php artisan test --filter=Auth

# Pengujian modul hak akses admin
php artisan test --filter=AdminTest

# Pengujian modul pelaporan barang
php artisan test --filter=LaporanTest

# Pengujian in-app chat
php artisan test --filter=ChatTest
```

**Status Pengujian Saat Ini**: `132 passed (390 assertions)` lulus tanpa kegagalan (*100% Pass*).

---

## 8. Lisensi & Hak Cipta

Proyek aplikasi web **TemuUNRI** dikembangkan sebagai luaran tugas perkuliahan Pemrograman Web Lanjut, Jurusan Teknik Informatika, Universitas Riau.
Dilindungi di bawah lisensi terbuka [MIT License](LICENSE).
