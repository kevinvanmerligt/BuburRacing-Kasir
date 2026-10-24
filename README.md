<div align="center">

# 🥣 Bubur Racing — Kasir

Aplikasi kasir berbasis web yang simpel, cepat, dan responsif untuk operasional kedai **Bubur Racing**.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

</div>

---

## ⚡ Fitur Utama

- **Menu Kasir**: Pilih menu bubur, tambah ekstra topping & minuman langsung masuk ke keranjang, hitung pajak otomatis, dan cetak struk.
- **Kelola Produk**: Tambah, edit, dan hapus menu makanan/minuman, lengkap dengan fitur crop gambar (1:1) dan pengaturan stok.
- **Riwayat Penjualan**: Pantau transaksi yang masuk, filter per tanggal, sortir pesanan, dan cari transaksi lewat nomor order.
- **Akun Kasir**: Login petugas kasir dengan proteksi keamanan, daftar akun baru, dan lupa sandi.

---

## 🚀 Cara Menjalankan

1. **Siapkan Server Lokal**  
   Pindahkan folder proyek ini ke direktori web server:
   - Laragon: `C:\laragon\www\KASIR`
   - XAMPP: `C:\xampp\htdocs\KASIR`

2. **Import Database**  
   Buka **phpMyAdmin** (`http://localhost/phpmyadmin`), buat database atau langsung import file:
   ```plaintext
   db_kasirbubur.sql
   ```

3. **Buka di Browser**  
   Akses lewat URL:
   ```plaintext
   http://localhost/KASIR/pages/login.php
   ```

---

## 🔑 Akun Login Bawaan

| Username | Password |
|---|---|
| `admin` | `admin123` |

*(Bisa juga langsung daftar akun baru lewat halaman Register).*

---

## 📁 Struktur Folder

```plaintext
├── assets/          # File css & js terpisah
├── components/      # Komponen tampilan (header, sidebar, modal, content)
├── connection/      # File koneksi database
├── pages/           # Halaman kasir, kelola produk, riwayat, login
├── uploads/         # Gambar produk
└── db_kasirbubur.sql # Database MySQL
```
