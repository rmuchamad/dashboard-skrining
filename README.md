# dashboard-skrining

Aplikasi dashboard skrining CKG (Cek Kesehatan Gratis) berbasis Laravel.

## Persyaratan

- PHP 8.2+
- Composer
- MySQL

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Salin `.env.example` ke `.env` lalu sesuaikan koneksi database sebelum menjalankan migrasi.

## Lisensi

MIT
