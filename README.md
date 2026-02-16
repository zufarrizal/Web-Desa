# Web Desa (CodeIgniter 4)

Aplikasi pelayanan administrasi desa berbasis CodeIgniter 4 untuk kebutuhan warga dan admin desa: pengelolaan dokumen, pengaduan, publikasi konten, dan halaman publik desa.

## Ringkasan Fitur
- Autentikasi warga: login, register, lupa password, reset password via email token.
- Role dan otorisasi: `admin` dan `user`.
- Dashboard terpisah untuk admin dan user.
- Manajemen pengguna oleh admin.
- Pelayanan dokumen warga:
  - generate surat,
  - input manual,
  - preview,
  - persetujuan admin,
  - print setelah disetujui,
  - update status.
- Pengaduan masyarakat dengan lampiran gambar.
- Konten publik terpisah: Program, Artikel, Kegiatan, Pengumuman.
- Halaman publik desa dengan dark/light mode.
- Halaman listing publik: `/postingan` + filter kategori.
- SEO dasar:
  - meta tags,
  - Open Graph,
  - Twitter Card,
  - `sitemap.xml`,
  - `robots.txt`.
- Halaman error kustom 404 dan 500 (production).

## Stack
- PHP `^7.4 || ^8.0` (disarankan PHP 8.x)
- CodeIgniter `4.4.8`
- MySQL / MariaDB
- PHPUnit 9

## Struktur Direktori Penting
- `app/Controllers`: logic controller.
- `app/Models`: model database.
- `app/Views`: tampilan admin/auth/publik.
- `app/Database/Migrations`: skema database.
- `app/Database/Seeds`: data seed.
- `app/Filters`: auth, role, security filters.
- `public/uploads`: file upload runtime.

## Instalasi
1. Clone repository.
2. Install dependency:
```bash
composer install
```
3. Copy `env` menjadi `.env`, lalu sesuaikan koneksi database:
```ini
CI_ENVIRONMENT = production

app.baseURL = 'http://localhost:8080/'

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
5. (Opsional) isi data awal (seed), lihat bagian **Database Seed (Lengkap)**.

## Menjalankan Aplikasi
### Opsi A: Built-in Server
```bash
php spark serve
```
Default: `http://localhost:8080`

### Opsi B: Apache / XAMPP
- Arahkan `DocumentRoot` ke folder `public`.
- Aktifkan `mod_rewrite`.
- Pastikan `AllowOverride All` aktif.

## Database Seed (Lengkap)
Seeder yang tersedia di `app/Database/Seeds`:
- `UserSeeder`:
  - membuat/memperbarui akun admin default `admin@example.com` (password: `Admin123!`).
- `LetterSettingSeeder`:
  - mengisi konfigurasi default profil desa, kontak, penandatangan, dan konten homepage.
- `ProgramSeeder`:
  - mengisi data dummy khusus Program.
- `ArticleSeeder`:
  - mengisi data dummy khusus Artikel.
- `ActivitySeeder`:
  - mengisi data dummy khusus Kegiatan.
- `AnnouncementSeeder`:
  - mengisi data dummy khusus Pengumuman.
- `ComplaintSeeder`:
  - mengisi data dummy pengaduan dengan beberapa status.

Urutan seed yang disarankan:
```bash
php spark db:seed UserSeeder
php spark db:seed LetterSettingSeeder
php spark db:seed ProgramSeeder
php spark db:seed ArticleSeeder
php spark db:seed ActivitySeeder
php spark db:seed AnnouncementSeeder
php spark db:seed ComplaintSeeder
```

Catatan:
- Jalankan `php spark migrate` terlebih dahulu sebelum seed.
- Beberapa seeder melakukan reset data tabel dummy agar hasil seed konsisten saat dijalankan ulang.

## Routing Utama
### Publik
- `GET /`: homepage.
- `GET /postingan`: listing semua postingan + filter `type`.
- `GET /program/{slug}`: detail posting.
- `GET /sitemap.xml`
- `GET /robots.txt`

### Auth
- `GET|POST /login`
- `GET|POST /register`
- `GET|POST /forgot-password`
- `GET|POST /reset-password/{token}`

### Protected (login)
- `/dashboard`
- `/profile`
- `/documents/*`
- `/complaints/*`

### Admin only
- `/users/*`
- `/programs/*`
- `/settings/home`

## Pengaturan Homepage oleh Admin
Menu: `Pengaturan Halaman Utama` (`/settings/home`)

Bisa mengatur:
- profil desa,
- kontak desa,
- info pengaduan,
- Plus Codes Google Maps kantor.

Format Plus Codes yang disarankan:
- `PLUSCODE, Nama Lokasi`
- Contoh: `6P5Q+23, Kantor Desa Sukamaju`

## Perilaku Upload Gambar Postingan
Untuk Program/Artikel/Kegiatan/Pengumuman:
- Format diterima: JPG, JPEG, PNG, WEBP.
- Jika ukuran <= 1 MB: disimpan normal.
- Jika ukuran > 1 MB: sistem kompres otomatis ke JPG sampai target <= 1 MB.
- Jika kompres gagal: upload dibatalkan.

Catatan:
- Auto-compress membutuhkan ekstensi PHP `gd` aktif.

## Alur Persetujuan Dokumen (Penting)
- Setiap surat baru berstatus awal `diajukan`.
- Surat harus disetujui admin terlebih dahulu (status `selesai`) sebelum bisa diprint.
- Tombol persetujuan tersedia untuk admin di:
  - tabel `Riwayat Surat`,
  - halaman `Preview Surat` (sebelah tombol print).
- Tombol print untuk user/admin akan aktif hanya jika surat sudah `selesai`.
- Akses URL print langsung juga divalidasi di backend; jika belum `selesai`, print ditolak.
- Tampilan preview **tidak** menampilkan tanda tangan.
- Tanda tangan hanya tampil pada halaman print/final output.

## Testing
Jalankan test:
```bash
vendor/bin/phpunit
```

Konfigurasi test database memakai grup `database.tests.*` di `.env`.
Pastikan kredensial test valid sebelum menjalankan test.

## Penjelasan Fitur Keamanan Penting
### 1. Autentikasi dan sesi
- Login wajib untuk semua fitur internal (`dashboard`, `documents`, `complaints`, dan lainnya).
- Session user dipakai untuk identitas dan role (`admin`/`user`).
- Endpoint internal penting menggunakan link token (`_lt`) untuk mengurangi akses tidak sah dari URL yang disalin sembarangan.

### 2. Otorisasi berbasis role
- Route admin dilindungi filter role (`role:admin`), jadi user biasa tidak bisa akses manajemen user, posting admin, dan pengaturan.
- Route yang butuh login dilindungi filter `auth`.
- Validasi otorisasi juga dilakukan di beberapa controller agar tidak hanya bergantung pada route.

### 3. Proteksi form dan anti-bot
- CSRF aktif untuk request form agar mencegah serangan cross-site request forgery.
- Honeypot dipakai untuk menahan bot form sederhana.
- Throttle/rate limit menurunkan risiko spam submit (misalnya login brute force ringan atau spam form).

### 4. Keamanan upload file
- Validasi upload aktif dan penyimpanan file runtime memakai nama acak di `public/uploads` untuk menurunkan risiko tebakan path file.
- Detail format/ukuran/kompres upload dijelaskan pada bagian **Perilaku Upload Gambar Postingan**.

### 5. Enkripsi data sensitif (at-rest)
- Data profil sensitif pada tabel `users` disimpan terenkripsi otomatis (contoh: NIK, alamat, tempat/tanggal lahir, dan field profil lain).
- Field `nik` pada tabel `document_requests` juga disimpan terenkripsi otomatis.
- Mekanisme enkripsi memakai:
  - `AES-256-CBC` untuk enkripsi isi data,
  - `HMAC-SHA256` untuk verifikasi integritas data (deteksi modifikasi/corrupt).
- Proses enkripsi/dekripsi berjalan di layer model (`beforeInsert`/`beforeUpdate`/`afterFind`), jadi controller tetap menerima nilai plaintext yang sudah didekripsi aman.
- Kunci enkripsi diambil dari `.env`:
  - prioritas: `app.profileDataKey`,
  - fallback: `encryption.key`.
- Jika key tidak diset, proses enkripsi akan gagal (runtime exception), jadi pengisian key ini wajib pada environment aktif.

### 6. Hardening server level
- `.htaccess` di root dan `public/` digunakan untuk pembatasan akses langsung file sensitif.
- Security headers dasar diterapkan untuk menambah proteksi browser-side.
- URL bersih tanpa `index.php` membantu konsistensi rule akses dan routing.

### 7. Error handling
- Halaman error kustom 404 dan 500 disediakan.
- Untuk production, detail sensitif error tidak ditampilkan ke user.

## Checklist Keamanan Sebelum Go-Live
- Set `CI_ENVIRONMENT=production`.
- Pastikan `.env` tidak pernah di-commit ke repository publik.
- Gunakan kredensial database kuat (jangan default kosong).
- Aktifkan HTTPS di server/reverse proxy.
- Pastikan permission folder `writable` dan `public/uploads` aman (hanya yang perlu write).
- Batasi ukuran upload di level PHP/web server sesuai kebutuhan (`upload_max_filesize`, `post_max_size`).
- Aktifkan ekstensi `gd` jika ingin fitur auto-compress gambar berjalan optimal.
- Pastikan `app.profileDataKey` terisi dan kuat (jangan gunakan key pendek/lemah).
- Monitoring log error di `writable/logs` secara berkala.

## Rekomendasi Lanjutan (Opsional)
- Tambahkan reCAPTCHA pada form sensitif (login, register, pengaduan) jika traffic publik tinggi.
- Terapkan pembatasan IP atau WAF di level infrastruktur.
- Tambahkan audit trail aktivitas admin (siapa mengubah apa dan kapan).
- Jadwalkan backup database harian + uji restore berkala.
- Siapkan prosedur rotasi key enkripsi terencana (karena data lama perlu proses re-encrypt jika key diganti).

## Lisensi
Project menggunakan lisensi MIT.
