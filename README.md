# EduNexus - Flat-File CMS Website Sekolah Modern

EduNexus adalah platform Content Management System (CMS) website sekolah yang dirancang khusus untuk kemudahan instalasi, keringanan performa, serta fleksibilitas desain yang sangat tinggi. Berbeda dari CMS tradisional yang membutuhkan database relasional rumit (seperti MySQL), EduNexus menggunakan arsitektur **Flat-File** di mana semua konfigurasi dan konten disimpan dalam bentuk berkas terstruktur JSON.

Website ini dilengkapi dengan sistem **Single-HTML Multi-Theme** yang terintegrasi dengan 5 variasi desain visual premium dari Google Stitch, yang dapat diubah secara instan langsung dari Dasbor Admin.

---

## 🌟 Fitur Utama (Core Features)

1. **Arsitektur Flat-File (Tanpa Database SQL)**
   * Seluruh konten dinamis, data profil, data akademik, alur PPDB, hingga konfigurasi sistem disimpan dalam file JSON di folder `config/`.
   * Memudahkan migrasi, backup (cukup salin folder proyek), dan sangat hemat konsumsi resource server.

2. **5 Pilihan Desain Premium (Google Stitch Themes)**
   * **Classic Academy**: Gaya bersih, profesional, dengan warna Navy/Biru dan sudut membulat standar.
   * **St. Jude Academy (Heritage)**: Desain prestisius berlatar Hijau Emerald dan Emas, dipadukan dengan tipografi serif klasik (`Libre Caslon Text`) dan pembatas berlian.
   * **Sekolah Cerdas (Modern Eco-Teal)**: Nuansa segar bertema kombinasi Oranye Terracotta & Teal, tombol elastis 3D flat, dan avatar Kepala Sekolah dengan animasi morfing organik.
   * **SMA Modern (Minimalist Corporate)**: Gaya modern berpresisi tinggi dengan sudut siku tajam (`2px`), garis outline tipis, dan nuansa korporat teknologi.
   * **EcoSchola (Organic Nature)**: Estetika ramah lingkungan bertema daun, efek bayangan tanah, tekstur serat kertas alami, serta tombol interaktif yang berubah bentuk menjadi daun saat disorot (hover).

3. **Dasbor Admin & Pengatur Brand Color Dinamis**
   * Panel admin mandiri berbasis AJAX/Fetch API yang aman untuk memperbarui seluruh konten sekolah tanpa reload halaman.
   * **Color Picker**: Admin dapat menentukan warna utama brand sekolah secara kustom yang otomatis memengaruhi aksen tombol, link, dan border di situs publik.

4. **Dynamic Hero Banner Slideshow**
   * Mendukung hingga 5 slide foto banner di halaman utama publik, lengkap dengan pengelolaan teks tajuk (headline), sub-headline, teks tombol aksi (CTA), dan tautan aksi yang dapat diatur dari dasbor admin.

5. **Integrasi Rich Text Editor (Quill WYSIWYG)**
   * Pengeditan Visi, Misi, dan Syarat Pendaftaran PPDB menggunakan editor teks kaya visual (Quill) untuk mendukung pemformatan tebal, miring, list, dan link secara rapi.

6. **Pengelolaan Data Dinamis (Dynamic Row Builder)**
   * Manajemen data Guru & Staf (dilengkapi unggah foto).
   * Program Keahlian / Jurusan Akademik.
   * Fasilitas Terbuka & Tertutup (dilengkapi unggah foto).
   * Jadwal Kegiatan Ekstrakurikuler.
   * Galeri Prestasi Siswa & Sekolah.
   * Langkah & Alur Pendaftaran PPDB.

7. **Blogger API Integration & Fallback**
   * Sinkronisasi berita otomatis secara *real-time* dari Google Blogger API. Jika API Key tidak dikonfigurasi, sistem otomatis beralih ke mode Pengumuman Internal Lokal yang disimpan secara flat-file.

8. **Keamanan & Keandalan Sesi**
   * Akses dasbor admin dilindungi enkripsi kata sandi berbasis `password_hash()` BCRYPT.
   * Fitur pembatasan sesi (*session inactivity timeout*) otomatis untuk mencegah akses tidak sah.

---

## 🛠️ Teknologi & Spesifikasi Teknis

* **Bahasa & Logic**: PHP 8.x (Back-end Routing & Flat-File Writer), Vanilla JavaScript ES6 (Dasbor AJAX, Slider, & Animasi).
* **UI Framework**: Bootstrap 5 (Struktur Grid, Utilitas Responsif, & Modul Dasbor).
* **Styling & Compile**: SCSS / Sass (Desain Modular & Variabel Tema), dikompilasi menggunakan **Vite** bundler untuk performa aset CSS tercepat.
* **Penyimpanan**: Flat JSON (`site_settings.json`, `home.json`, `profile.json`, `academic.json`, `ppdb.json`, `contact.json`).
* **Icons & Fonts**: Google Material Symbols Outlined, Bootstrap Icons, dan Google Fonts Integration (`Inter`, `Outfit`, `Quicksand`, `Playfair Display`, `Source Sans 3`).

---

## 📁 Struktur Direktori Utama

```text
├── assets/                  # File hasil build aset (CSS terkompilasi, JS, & Gambar Unggahan)
│   ├── css/style.css        # Output CSS final hasil kompilasi SCSS
│   ├── js/main.js           # File JS publik hasil build Vite
│   └── images/              # Folder penyimpanan unggahan gambar/logo/foto kepsek
├── config/                  # Database Flat-File (Berkas JSON)
│   ├── site_settings.json   # Konfigurasi logo, nama sekolah, warna brand, & tema aktif
│   ├── home.json            # Konten beranda, slide hero, sambutan kepsek, berita lokal, & statistik
│   ├── profile.json         # Visi, misi, sejarah, & daftar guru
│   ├── academic.json        # Deskripsi kurikulum, daftar jurusan, fasilitas, & kalender akademik
│   ├── ppdb.json            # Syarat daftar & alur pendaftaran
│   └── contact.json         # Alamat, telepon, email, & koordinat Google Maps
├── dashboard/               # Modul CMS Administrator
│   ├── index.php            # Halaman utama dasbor admin
│   ├── login.php            # Form autentikasi admin
│   ├── logout.php           # Proses penghancuran sesi admin
│   ├── save_config.php      # Controller AJAX untuk validasi & penyimpanan data ke JSON
│   └── credentials.php      # Penyimpanan hash username & password admin
├── includes/                # Komponen Layout Bersama (Shared Components)
│   ├── header.php           # Impor CSS, Google Fonts, & inisialisasi kelas tema aktif
│   ├── navbar.php           # Navigasi utama publik (responsif)
│   └── footer.php           # Kaki halaman publik & impor Bootstrap JS
├── views/                   # Template Halaman Publik
│   ├── home.php             # Tampilan Beranda (Slide Carousel, Sambutan, & Berita)
│   ├── profile.php          # Tampilan Sejarah, Visi Misi, & Daftar Guru
│   ├── academic.php         # Tampilan Jurusan & Daftar Fasilitas
│   ├── contact.php          # Tampilan Informasi Kontak & Peta Google Maps
│   └── ppdb.php             # Tampilan Syarat Masuk & Alur Pendaftaran
├── src/                     # Source Code Pembangunan Aset (Vite)
│   ├── style.scss           # File SCSS utama tempat pemetaan 5 tema desain
│   └── main.js              # Source JS utama
├── index.php                # Entrypoint website & file routing halaman publik
├── package.json             # Dependensi Node.js & konfigurasi script build Vite
└── README.md                # Dokumentasi proyek proposal
```

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### Prasyarat (Prerequisites)
1. **PHP 8.0** atau versi lebih tinggi terinstal di komputer.
2. **Node.js (v16+)** & npm untuk mengompilasi aset CSS (jika ingin memodifikasi file SCSS).

### Langkah-Langkah Menjalankan:

1. **Jalankan PHP Development Server**
   Buka terminal di direktori root proyek dan jalankan:
   ```bash
   php -S localhost:8000
   ```
   Buka peramban dan akses: `http://localhost:8000/`.

2. **Masuk ke Dasbor Admin**
   * Akses URL: `http://localhost:8000/dashboard/`.
   * Username Default: `admin`
   * Password Default: `AdminSekolah2026` *(Dapat diubah langsung di Tab Keamanan dasbor admin)*.

3. **Mengompilasi Ulang CSS (Developer Mode)**
   Jika Anda melakukan kustomisasi kode warna atau layout di berkas `src/style.scss`, lakukan instalasi dependensi npm dan jalankan compiler:
   ```bash
   npm install
   npm run build
   ```

---

## 📄 Ringkasan Dokumen Proposal
Website ini menawarkan solusi website sekolah yang sangat hemat biaya hosting, cepat dimuat, aman dari serangan injeksi database SQL, dan memiliki nilai estetika tinggi yang memukau mata pengunjung berkat pilihan tema siap pakai. Sangat cocok direkomendasikan untuk sekolah dasar (SD), sekolah menengah (SMP/SMA), maupun sekolah kejuruan (SMK) yang membutuhkan digitalisasi institusi secara instan dan profesional.
