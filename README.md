# KargoKita

KargoKita adalah aplikasi berbasis web modern untuk memantau (live tracking) dan mengelola logistik pengiriman kargo secara *real-time*. Dibangun menggunakan ekosistem Laravel dan Livewire, KargoKita menawarkan antarmuka yang dinamis, estetis, dan intuitif untuk memantau aktivitas armada kargo.

## Fitur Utama

- **Live Tracking Armada**: Memantau posisi truk kargo secara *real-time*.
  - **Peta Leaflet Live**: Visualisasi rute terencana vs rute riil (deviasi) menggunakan Leaflet.js dan indikator koordinat GPS terkini.
  - **Skematik Perjalanan (Linear Progress)**: Indikator visual proporsional perjalanan armada dari titik awal ke titik akhir, lengkap dengan titik-titik singgah (Drop Points).
  - **Topologi Rute**: Daftar urutan lokasi pemberhentian dan representasi tekstual dari estimasi pergerakan lokasi terkini armada.
- **Event Audit & Deviasi**: Pencatatan otomatis apabila terjadi deviasi (keluar jalur) atau *dwell* (pemberhentian) di luar rencana pengiriman.
- **Modern & Responsive UI**: Antarmuka dirancang dengan *Tailwind CSS* untuk menyesuaikan pada segala ukuran layar (mobile, tablet, desktop). Dilengkapi dengan skema warna khusus yang estetik (dark mode dan skema warna biru korporat).
- **Interaktivitas Tanpa Refresh**: Integrasi *Laravel Livewire* memungkinkan navigasi *tab* antara Peta, Skematik, dan Topologi dengan sangat mulus dan cepat.

## Teknologi yang Digunakan

- **Backend Core**: [Laravel 11.x](https://laravel.com/) (PHP 8.3+)
- **Interactive UI**: [Laravel Livewire 3](https://livewire.laravel.com/) & Blade Templating
- **Styling**: [Tailwind CSS v3](https://tailwindcss.com/)
- **Maps**: [Leaflet.js](https://leafletjs.com/) (dengan plugin Routing Machine)
- **Database**: MySQL / MariaDB (via Eloquent ORM)

## Prasyarat Sistem

Sebelum menginstall, pastikan sistem Anda telah memiliki perangkat lunak berikut:
- PHP >= 8.2 (Disarankan 8.3)
- Composer
- Node.js & NPM
- MySQL atau MariaDB

## Instalasi & Cara Menjalankan (Local Development)

1. **Clone repository proyek ini**
   ```bash
   git clone https://github.com/Fik31/kargokita.git
   cd kargokita
   ```

2. **Install dependensi (PHP & Node.js)**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`, lalu atur koneksi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Jalankan Migrasi & Seeding**
   Siapkan tabel database dan isi dengan *dummy data* pengiriman dan armada:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Kompilasi Aset Frontend (Vite)**
   Jalankan server Vite untuk mengkompilasi file Tailwind CSS secara *real-time*:
   ```bash
   npm run dev
   ```

6. **Jalankan Aplikasi Web**
   Buka terminal baru pada direktori proyek, lalu jalankan server Laravel:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di alamat: `http://127.0.0.1:8000`

## Dokumentasi & Struktur Berkas Utama

- **Halaman Utama**: `resources/views/welcome.blade.php`
- **Dashboard Tracking**: `resources/views/livewire/live-tracking.blade.php`
- **Konfigurasi Tema**: `tailwind.config.js`

## Lisensi

Proyek ini dibuat untuk keperluan *development* dan mengikuti standar pengembangan berbasis agen AI dari ekosistem *Laravel*.
