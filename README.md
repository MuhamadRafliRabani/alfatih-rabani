# Admin Panel al fatih

Admin Panel dengan **Laravel + Inertia.js + Vue 3** untuk mengelola data pasien dan riwayat kunjungan.

## Prerequisites

- PHP >= 8.x  
- Composer  
- Node.js >= 18.x & npm / yarn / pnpm  
- Database: MySQL / MariaDB / PostgreSQL  
- Git  

## Clone Repository

```bash
git clone https://github.com/username/project-name.git
cd project-name

## Install Dependencies

```bash
composer install
npm install
```

## Setup Environment

- copy .env.excampel terus ubah ke .env
- ubah konfigurasi aplikasi dan DB
``` bash
APP_NAME="AdminPanel"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admin_panel_db
DB_USERNAME=root
DB_PASSWORD=
```
- sesaikan dengan DB kamu

## Generate App Key

```bash
php artisan key:generate
```

## Database Migration & Seeder

```bash
php artisan migrate --seed
```
Seeder default membuat akun admin:
Email: admin@example.com
Password: password123

## Menjalankan Project

```bash
php artisan serve
npm run dev
```

## Masuk ke dashboad
- login atau buat akun dihomepage
- setelah login akan redirect kedashboad secara otomatis

## Catatan
### Konfigurasi CRUD 
- create: Prefix/store
- update: Prefix/update
- delete: Prefix/delete

