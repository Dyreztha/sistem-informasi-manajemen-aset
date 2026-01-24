# SIMA – Sistem Informasi Manajemen Aset

## Persyaratan
- PHP 8.2+
- Composer
- Node.js 18+
- Database (MySQL/PostgreSQL/SQLite)

## Instalasi
1. Install dependency backend:
	- `composer install`
2. Install dependency frontend:
	- `npm install`
3. Salin file env:
	- `copy .env.example .env`
4. Set konfigurasi database di file `.env`.
5. Generate app key:
	- `php artisan key:generate`
6. Migrasi dan seeding:
	- `php artisan migrate:fresh --seed`
7. Build asset frontend:
	- `npm run build`

## Menjalankan Aplikasi
1. Jalankan server Laravel:
	- `php artisan serve`
2. Buka aplikasi di browser:
	- `http://127.0.0.1:8000`

## Akun Default (Seeder)
Gunakan akun berikut setelah seeding:
- Admin: admin@sima.com / password
- Manager: manager@sima.com / password
- Staff: staff@sima.com / password
- Auditor: auditor@sima.com / password

## Catatan
- Halaman `/assets` memerlukan login.
- Jika ada perubahan UI, lakukan `npm run build` ulang.
