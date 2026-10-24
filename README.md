<div align="center">

# 🥣 KASIR BUBUR RACING
### *Modern Point of Sale (POS) & Restaurant Management System*

[![PHP Version](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

<p align="center">
  Aplikasi kasir web responsif dan modular untuk operasional kedai bubur, dirancang dengan sistem bundling produk otomatis, perhitungan pajak real-time, manajemen stok, dan pelaporan riwayat transaksi.
</p>

</div>

---

## 📌 Daftar Isi
- [Fitur Utama](#-fitur-utama)
- [Arsitektur & Pemisahan Kode](#-arsitektur--pemisahan-kode)
- [Struktur Direktori](#-struktur-direktori)
- [Tech Stack](#-tech-stack)
- [Panduan Instalasi & Menjalankan](#-panduan-instalasi--menjalankan)
- [Skema Basis Data](#-skema-basis-data)
- [Alur Penggunaan Aplikasi](#-alur-penggunaan-aplikasi)
- [Kredensial Default](#-kredensial-default)

---

## ✨ Fitur Utama

### 🛒 1. Kasir & Point of Sale (`produk.php`)
* **Sistem Bundling Makanan & Toping**: Logika cerdas yang menggabungkan toping ke dalam menu bubur yang dipilih secara hierarkis.
* **Keranjang Belanja Real-Time**: Update instan kuantitas pesanan, pengurangan kuota stok live, dan kalkulasi subtotal.
* **Kalkulasi Pajak & Kembalian Otomatis**: Perhitungan pajak resto (11%) otomatis dan kalkulasi kembalian uang tunai dengan tombol nominal cepat (*Uang Pas, 50k, 100k*).
* **Multiple Payment Support**: Tunai (Cash), QRIS, dan Debit.
* **Proses Transaksi Asinkron (AJAX)**: Checkout tanpa reload halaman melalui Fetch API dengan umpan balik modal sukses.

### 📦 2. Kelola Produk & Inventaris (`kelolaproduk.php`)
* **CRUD Produk**: Penambahan, pengeditan, dan penghapusan menu makanan, toping, maupun minuman.
* **Image Crop Tool (Cropper.js)**: Pemotongan gambar produk otomatis dengan rasio 1:1 sebelum disimpan, mendukung drag-and-drop file gambar.
* **Status Ketersediaan**: Pengaturan cepat status produk (*Tersedia / Habis*) serta sinkronisasi otomatis bila stok kosong.
* **Auto Format Rupiah**: Input harga interaktif dengan pemisah ribuan otomatis.

### 📜 3. Riwayat Transaksi & Pelaporan (`riwayat.php`)
* **Live Search ID Transaksi**: Pencarian instan berdasarkan kode transaksi `#BR-xxxx` tanpa refresh halaman.
* **Filter Tanggal Fleksibel**: Menyaring penjualan berdasarkan hari tertentu dengan opsi pembersihan filter satu klik.
* **Multi-Criteria Sorting**: Mengurutkan transaksi berdasarkan:
  * Waktu (*Terbaru ke Lama / Lama ke Terbaru*)
  * Total Transaksi (*Tertinggi ke Terendah / Terendah ke Tertinggi*)
  * ID Transaksi (*A - Z / Z - A*)
* **Detail Struk Digital**: Modal popup rincian produk, kuantitas, harga satuan, pajak, uang bayar, dan kembalian.

### 🔐 4. Autentikasi & Keamanan (`auth`)
* Login dengan proteksi brute-force (pembatasan 3x percobaan salah sandi).
* Registrasi kasir/admin baru.
* Fitur lupa kata sandi (*Reset Password*).
* Proteksi sesi halaman (`session_start()` & validasi akun aktif).

---

## 🧩 Arsitektur & Pemisahan Kode

Aplikasi ini telah direfaktor menggunakan prinsip **Separation of Concerns (SoC)** dan **Component-Based Architecture**:

1. **Pages (`pages/`)**: Berperan sebagai Controller & View utama yang hanya menangani query SQL backend, otentikasi sesi, dan pemanggilan komponen via `include`.
2. **Components (`components/`)**: Berisi modul HTML reusable (Header, Sidebar, Modals, Content Area) yang bersih dari script logika berat.
3. **External JavaScript (`assets/js/`)**: Seluruh logika JavaScript telah dipisahkan ke file `.js` murni tanpa inline script tag di PHP, mempermudah pemeliharaan dan caching browser.

---

## 📂 Struktur Direktori

```plaintext
KASIR/
│
├── assets/                       # Aset publik statis
│   ├── css/
│   │   └── style.css             # Kustom stylesheet
│   └── js/
│       ├── produk.js             # Logika keranjang & transaksi checkout
│       ├── kelolaproduk.js       # Logika form produk & cropper gambar
│       ├── riwayat.js            # Logika filter riwayat, sorting, & modal detail
│       └── script.js             # Shared utilities
│
├── components/                   # Komponen modular reusable
│   ├── head_assets.php           # CDN Tailwind, Google Fonts, & shared styles
│   ├── header.php                # Top navigation bar (dynamic title & search)
│   ├── sidebar.php               # Side menu bar (active indicator)
│   ├── product_content.php       # Grid katalog menu bubur/toping/minuman
│   ├── cart_sidebar.php          # Panel keranjang belanja & ringkasan biaya
│   ├── modals.php                # Modal pembayaran & alert kustom
│   ├── kelola_content.php        # Tampilan tabel & card kelola inventaris
│   ├── kelola_modals.php         # Modal form produk, cropper, & delete
│   ├── riwayat_content.php       # Tabel daftar riwayat transaksi & toolbar
│   └── riwayat_modal.php         # Modal detail struk riwayat order
│
├── connection/
│   └── koneksi.php               # Konfigurasi koneksi MySQLi
│
├── pages/                        # Halaman aplikasi utama
│   ├── login.php                 # Halaman login kasir
│   ├── reg.php                   # Halaman registrasi kasir baru
│   ├── forget.php                # Halaman reset / lupa password
│   ├── produk.php                # Halaman utama Point of Sale (POS)
│   ├── kelolaproduk.php          # Halaman manajemen produk & stok
│   └── riwayat.php               # Halaman riwayat penjualan
│
├── uploads/                      # Direktori upload foto produk (base64 processed)
├── db_kasirbubur.sql             # Dump skema database MySQL & sample data
└── README.md                     # Dokumentasi proyek
```

---

## 🛠 Tech Stack

* **Backend**: PHP 8.x (Native / Procedural + MySQLi Prepared Statements)
* **Database**: MySQL / MariaDB
* **Frontend**: Vanilla JavaScript (ES6+), HTML5 Semantic
* **Styling**: Tailwind CSS (CDN & Standalone Engine)
* **Libraries**:
  * [Cropper.js](https://fengyuanchen.github.io/cropperjs/) (Pemotong gambar di sisi klien)
  * [Google Fonts](https://fonts.google.com/) (*Squada One*, *Inter*, *Playfair Display*)

---

## 🚀 Panduan Instalasi & Menjalankan

### Prasyarat
* Web Server lokal: **Laragon** (Direkomendasikan) atau **XAMPP**
* PHP versi **8.0 atau lebih tinggi**
* MySQL Database Server

### Langkah-langkah
1. **Clone atau letakkan project** di direktori web server:
   * Laragon: `C:\laragon\www\KASIR`
   * XAMPP: `C:\xampp\htdocs\KASIR`

2. **Jalankan Apache & MySQL** melalui panel Laragon / XAMPP.

3. **Impor Database**:
   * Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) atau HeidiSQL.
   * Buat database baru bernama `db_kasirbubur` (opsional, script SQL otomatis membuatnya).
   * Impor file `db_kasirbubur.sql` yang berada di root direktori project ini.

4. **Konfigurasi Database (Jika Diperlukan)**:
   * Buka file [**`connection/koneksi.php`**](file:///c:/laragon/www/KASIR/connection/koneksi.php):
     ```php
     $username = "root";
     $password = "";
     $host = "localhost";
     $database = "db_kasirbubur";
     ```
   * Sesuaikan `$password` jika akun MySQL Anda memiliki kata sandi.

5. **Akses Aplikasi**:
   * Buka browser dan kunjungi:
     ```
     http://localhost/KASIR/pages/login.php
     ```

---

## 🗄 Skema Basis Data

| Tabel | Deskripsi | Kolom Utama |
|---|---|---|
| **`users`** | Akun pengguna / kasir terdaftar | `id`, `username`, `email`, `password`, `created_at` |
| **`produk`** | Katalog makanan, minuman, dan topping | `id_barang`, `nama_barang`, `jenis`, `harga_barang`, `stok`, `status`, `foto` |
| **`transaksi`** | Rekam jejak transaksi penjualan | `id_transaksi`, `id_barang`, `harga_produk`, `total`, `uang`, `kembalian`, `nama`, `meja`, `pajak`, `metode`, `tgl` |

---

## 🔑 Kredensial Default

Setelah mengimpor `db_kasirbubur.sql`, Anda dapat langsung masuk dengan akun bawaan berikut:

* **Username**: `admin`
* **Password**: `admin123`

*(Anda juga dapat membuat akun baru secara instan melalui menu **Daftar Akun**).*

---

## 🤝 Kontribusi & Lisensi

Proyek ini dibuat untuk keperluan sistem manajemen kasir kedai makanan. Silakan lakukan *fork*, penyesuaian, atau pembuatan *pull request* untuk pengembangan lebih lanjut.

Lisensi di bawah [MIT License](LICENSE).
