# Web Desa (CodeIgniter 4)

Aplikasi pelayanan administrasi desa berbasis CodeIgniter 4 dengan role `admin` dan `user`, layanan surat warga, pengaduan, dan publikasi konten desa.

## Fitur Utama
- Autentikasi warga: login, registrasi, lupa password, reset password via email
- Role & otorisasi: `admin` dan `user`
- Dashboard admin/user dengan pemisahan menu fitur
- Managemen Pengguna (CRUD user oleh admin)
- Pelayanan pengurusan dokumen warga + preview/print surat
- Pengaduan masyarakat
- Posting konten terpisah: Program Desa, Artikel, Kegiatan
- Halaman publik (landing page) + dark mode/light mode
- SEO dasar: meta tags, Open Graph, Twitter Card, `sitemap.xml`, `robots.txt`
- URL bersih tanpa `index.php`

## Stack
- PHP 8.x
- CodeIgniter 4.4.x
- MySQL / MariaDB
- Apache (XAMPP) atau `php spark serve`

## Instalasi
1. Clone repository.
2. Install dependency:
```bash
composer install
```
3. Buat file `.env` dari `env`, lalu isi koneksi database:
```ini
database.default.hostname = localhost
database.default.database = web_desa
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```
4. Jalankan migrasi:
```bash
php spark migrate
```
5. (Opsional) isi data dummy konten:
```bash
php spark db:seed ProgramPostSeeder
```

## Menjalankan Aplikasi
### Opsi A: Built-in Server
```bash
php spark serve
```
Akses: `http://localhost:8080`

### Opsi B: Apache / XAMPP
- Arahkan `DocumentRoot` ke folder `public`
- Pastikan `mod_rewrite` aktif
- Pastikan `AllowOverride All` aktif agar `.htaccess` bekerja

## Keamanan yang Sudah Diaktifkan
- CSRF protection untuk request form
- Honeypot otomatis pada form
- Rate limiting/throttling request dan submit form
- Link token filter untuk endpoint internal yang dilindungi
- Hardening `.htaccess` di root project dan `public/`
- Header keamanan dasar di `public/.htaccess`

## Struktur Direktori Ringkas
- `app/Controllers` controller aplikasi
- `app/Views` tampilan admin, auth, publik
- `app/Database/Migrations` skema database
- `app/Database/Seeds` data dummy
- `app/Filters` filter keamanan/autentikasi
- `public/uploads` file upload runtime (tidak di-commit)

## Catatan Git
- File sensitif seperti `.env`, log, cache, session, dan upload runtime tidak di-commit
- Line ending dinormalisasi melalui `.gitattributes`
