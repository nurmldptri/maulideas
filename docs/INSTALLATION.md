# 🚀 Cara Instalasi Aplikasi

## 📋 Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL 5.7+
- Apache/Nginx Web Server
- phpMyAdmin (opsional)
- Composer (jika menggunakan dependency tambahan)

## ⚙️ Langkah Instalasi
1. Download atau clone repository
   ```bash
   git clone https://github.com/username/photostrip-maulideas.git
   
2. Pindahkan ke direktori web server, misalnya htdocs atau public_html.

3. Buat database baru melalui phpMyAdmin/MySQL CLI:
   CREATE DATABASE photostrip_app;

4. Import struktur database yang ada di folder db/photostrip_app.sql.

5. Edit file konfigurasi database:
   File: inc/koneksi.php

   $host = 'localhost';
   $db   = 'photostrip_app';
   $user = 'root';
   $pass = '';

6. Jalankan di browser:
   http://localhost/photostrip-maulideas/

7. Login Sebagai;
   Admin    : admin@photostrip.com / admin123
   Pengguna : putri@gmail.com / putri
