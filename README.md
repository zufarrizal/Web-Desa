# Web Desa (CodeIgniter 4)

Aplikasi pelayanan administrasi desa berbasis CodeIgniter 4.

## Fitur Utama
- Autentikasi: login, register warga, lupa password, reset password
- Role: `admin` dan `user`
- Manajemen pengguna (admin CRUD user)
- Pelayanan dokumen warga
- Pengaduan masyarakat
- Posting konten terpisah:
  - Program Desa
  - Artikel
  - Kegiatan
- Halaman publik/home + dark/light mode
- SEO dasar (meta, sitemap, robots)

## Stack
- PHP 8.x
- CodeIgniter 4.4.8
- MySQL / MariaDB
- Apache (XAMPP) atau `php spark serve` untuk development

## Persiapan
1. Clone project.
2. Install dependency:
```bash
composer install
```
3. Buat file `.env` dari `env` lalu isi konfigurasi database:
```ini
database.default.hostname = localhost
database.default.database = web_desa
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```
4. Jalankan migration:
```bash
php spark migrate
```

## Seeder Dummy Konten
Untuk membuat data dummy postingan (masing-masing 5 data untuk program, artikel, kegiatan):
```bash
php spark db:seed ProgramPostSeeder
```

## Menjalankan Aplikasi
### Opsi A: Spark (development cepat)
```bash
php spark serve
```
Default: `http://localhost:8080`

### Opsi B: Apache/XAMPP
- Set `DocumentRoot` ke folder `public`
- Pastikan `mod_rewrite` aktif

## Keamanan Dasar
- Root project diberi `.htaccess` deny-all (antisalah konfigurasi DocumentRoot)
- `public/.htaccess` berisi hardening file sensitif + header keamanan dasar
- CSRF dari CodeIgniter aktif pada form POST

## Struktur Singkat
- `app/Controllers` - controller utama
- `app/Views` - tampilan admin/public/auth
- `app/Database/Migrations` - skema database
- `app/Database/Seeds` - data dummy awal
- `public/uploads` - file upload runtime (di-ignore git)

## Catatan Git
- File sensitif seperti `.env`, log, dan upload runtime tidak di-commit.
- Line ending dinormalisasi lewat `.gitattributes`.

