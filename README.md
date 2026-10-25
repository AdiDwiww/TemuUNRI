<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Menjalankan TemuUNRI setelah clone

Prasyarat: PHP 8.3 atau lebih baru, Composer, dan Node.js 24 LTS (termasuk npm dan npx).
Untuk konfigurasi bawaan SQLite, aktifkan ekstensi PHP `pdo_sqlite` dan `sqlite3`.

Di terminal pada folder proyek, jalankan sekali setelah clone:

```powershell
composer run setup
```

Perintah ini memasang dependensi PHP dan JavaScript, menyalin `.env.example` jika
`.env` belum ada, membuat `APP_KEY`, menjalankan migrasi, dan membangun aset frontend.
Konfigurasi bawaan menggunakan SQLite sehingga MySQL tidak perlu dinyalakan.
Gunakan perintah setup untuk instalasi awal karena perintah ini membuat ulang `APP_KEY`.

Untuk menjalankan aplikasi setiap hari:

```powershell
composer run dev
```

Buka http://127.0.0.1:8000. Perintah ini menjalankan server Laravel, antrean, dan Vite.
Tekan `Ctrl+C` untuk menghentikannya.

Jika `npx` tidak dikenali, pasang Node.js LTS dari https://nodejs.org/en/download,
lalu tutup dan buka kembali terminal atau VS Code. Periksa dengan `node -v`,
`npm.cmd -v`, dan `npx.cmd -v`. Jika PowerShell memblokir `npm.ps1`, gunakan
`npm.cmd` untuk menjalankan npm secara langsung.

Pail tidak dijalankan oleh skrip `dev` karena memerlukan ekstensi PCNTL yang tidak
tersedia pada PHP Windows. Untuk melihat log pada terminal PowerShell terpisah:

```powershell
Get-Content .\storage\logs\laravel.log -Tail 50 -Wait
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
