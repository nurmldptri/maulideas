-- MEMBUAT DAN MENGGUNAKAN DATABASE
CREATE DATABASE IF NOT EXISTS photostrip_app;
USE photostrip_app;

-----------------------------------------------------------------------------------------------------------------
-- 1. TABLE USERS
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(64) NOT NULL, -- SHA-256 hash
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users` ADD `is_approved` TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE `users` DROP COLUMN `is_approved`;

INSERT INTO `users` (`name`, `username`, `email`, `password`, `role`)
VALUES 
('Admin', 'admin', 'admin@photostrip.com', SHA2('admin123', 256), 'admin');

UPDATE users SET name = 'Admin' WHERE id = 1;
UPDATE users SET name = 'Maisur' WHERE id = 3;

ALTER TABLE users MODIFY name VARCHAR(100) NULL;

-----------------------------------------------------------------------------------------------------------------
-- 2. TABLE ORDERS
CREATE TABLE `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `wa` VARCHAR(20) NOT NULL,
  `metode` ENUM('Transfer', 'COD', 'E-Wallet') NOT NULL DEFAULT 'Transfer',
  `status` ENUM('Diproses', 'Selesai', 'Batal') NOT NULL DEFAULT 'Diproses',
  `bukti` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `orders`
ADD COLUMN `user_id` INT(11) DEFAULT NULL,
ADD CONSTRAINT `fk_orders_user`
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
  ON DELETE SET NULL;

ALTER TABLE `orders` ADD COLUMN `rincian` TEXT DEFAULT NULL;
ALTER TABLE `orders` ADD COLUMN `alamat` TEXT DEFAULT NULL;

INSERT INTO `orders` (`nama`, `wa`, `metode`, `status`, `rincian`, `bukti`, `alamat`, `created_at`)
VALUES 
(
  'Nur Maulida Putri', 
  '0895327994255', 
  'Transfer', 
  'Diproses', 
  '1E (8)',
  'bukti_tf.jpg', 
  'Jl. Zamrud 10', 
  NOW()
);

ALTER TABLE orders ADD COLUMN kode_pesanan VARCHAR(100);

SELECT p.*, o.user_id 
FROM pembayaran p
JOIN orders o ON p.order_id = o.id
WHERE o.user_id = 1;

INSERT INTO orders (user_id, status) VALUES (1, 'Menunggu Pembayaran');
INSERT INTO orders (user_id, status) VALUES (3, 'Menunggu Pembayaran');

-----------------------------------------------------------------------------------------------------------------
-- 3. TABLE BLOGS
CREATE TABLE `blogs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `author_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`author_id`) REFERENCES users(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO blogs (title, content, author_id) VALUES
-- Artikel 1
(
  '5 Alasan Kenapa Kamu Harus Order di Photostrip Maulideas!',
  'Photostrip Maulideas bukan sekadar jasa cetak biasa. Kami hadir untuk menjadikan setiap momenmu lebih berkesan dengan desain unik dan kualitas terbaik. 
Berikut 5 alasan kenapa kamu wajib order di sini:
1. 💗 Desain eksklusif dan kekinian yang bisa kamu pilih sesuai tema acara.
2. 📸 Hasil cetak jernih, warna tajam, dan tahan lama.
3. 📦 Tersedia pengiriman ke seluruh Indonesia.
4. 💬 Fast response & CS yang ramah banget!
5. 💰 Harga terjangkau dengan banyak promo menarik.

Jangan tunggu lagi, abadikan kenanganmu sekarang juga di Photostrip Maulideas!',
  1
),
-- Artikel 2
(
  'Cara Order Photostrip di Maulideas, Gampang Banget!',
  'Gak perlu ribet, kamu bisa order photostrip favoritmu hanya dalam 4 langkah mudah:
1. 📥 Pilih desain favorit dari galeri kami di halaman Desain.
2. 📝 Isi data pesanan di halaman Pemesanan dan upload foto kamu.
3. 💳 Pilih metode pembayaran (Transfer, COD, E-Wallet).
4. 🧾 Upload bukti pembayaran, lalu tinggal tunggu pesananmu dikirim!

Kami akan konfirmasi dan cetak dalam waktu maksimal 1x24 jam. Yuk mulai pesan sekarang!',
  1
),
-- Artikel 3
(
  'Tips Bikin Foto Kamu Makin Aesthetic Buat Cetak Photostrip',
  'Biar hasil photostrip kamu makin kece, ikuti tips berikut ini:
✨ Gunakan background polos atau dengan cahaya alami.
📸 Pakai kamera HP resolusi tinggi, hindari blur.
👕 Gunakan outfit warna senada atau kontras biar lebih standout.
😊 Ekspresi bebas! Boleh pose lucu, imut, atau elegan sesuai gayamu.

Dengan sedikit effort, photostrip kamu dijamin jadi kenangan manis yang bisa kamu simpan selamanya.',
  1
);

SELECT b.*, u.username FROM blogs b JOIN users u ON b.author_id = u.id ORDER BY b.created_at DESC;

-----------------------------------------------------------------------------------------------------------------
-- 4. TABLE PRODUK
CREATE TABLE produk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  foto VARCHAR(255) NOT NULL,
  nama_produk VARCHAR(100) NOT NULL,
  harga INT NOT NULL,
  stok INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO produk (foto, nama_produk, harga, stok, created_at) VALUES
('desain1.jpg', 'Photostrip Desain 1 (5 Lembar)', 15000, 25, '2025-07-01 09:00:00'),
('desain2.jpg', 'Photostrip Desain 2 (5 Lembar)', 15000, 25, '2025-07-01 09:00:00'),
('desain3.jpg', 'Photostrip Desain 3 (5 Lembar)', 15000, 25, '2025-07-01 09:00:00'),
('desain4.jpg', 'Photostrip Desain 4 (5 Lembar)', 15000, 25, '2025-07-01 09:00:00'),
('desaincombo.jpg', 'Photostrip Combo (5 Lembar)', 15000, 25, '2025-07-01 09:00:00');

-----------------------------------------------------------------------------------------------------------------
-- 5. TABLE PEMBAYARAN
-- TABEL PEMBAYARAN
CREATE TABLE `pembayaran` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `metode` ENUM('Transfer','COD','E-Wallet') NOT NULL,
  `bukti_pembayaran` VARCHAR(255),
  `status` ENUM('Menunggu','Dikonfirmasi','Ditolak') DEFAULT 'Menunggu',
  `tanggal_bayar` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-----------------------------------------------------------------------------------------------------------------
-- 6. TABLE PENGIRIMAN
CREATE TABLE `pengiriman` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `kurir` VARCHAR(100),
  `resi` VARCHAR(100),
  `status` ENUM('Belum Dikirim','Dikirim','Diterima') DEFAULT 'Belum Dikirim',
  `tanggal_kirim` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-----------------------------------------------------------------------------------------------------------------
-- 7. TABLE PELANGGAN
CREATE TABLE pelanggan (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  alamat TEXT,
  no_wa VARCHAR(20),
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-----------------------------------------------------------------------------------------------------------------
-- 8. TABLE DESAIN
CREATE TABLE desain (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  file VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO desain (nama, file)
VALUES
('Desain 1', '1.png'),
('Desain 2', '2.png'),
('Desain 3', '3.png'),
('Desain 4', '4.png'),
('Desain 5', '5.png'),
('Desain 6', '6.png'),
('Desain 7', '7.png'),
('Desain 8', '8.png'),
('Desain 9', '9.png'),
('Desain 10', '10.png'),
('Desain 11', '11.png'),
('Desain 12', '12.png'),
('Desain 13', '13.png'),
('Desain 14', '14.png'),
('Desain 15', '15.png'),
('Desain 16', '16.png'),
('Desain 17', '17.png'),
('Desain 18', '18.png'),
('Desain 19', '19.png'),
('Desain 20', '20.png'),
('Desain 21', '21.png'),
('Desain 22', '22.png'),
('Desain 23', '23.png'),
('Desain 24', '24.png');

-----------------------------------------------------------------------------------------------------------------
-- 9. TABLE LAPORAN ADMIN
CREATE TABLE laporan_admin (
  id INT(11) NOT NULL AUTO_INCREMENT,
  admin_id INT(11) NOT NULL,
  nama_admin VARCHAR(100) NOT NULL,
  waktu_login DATETIME NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (admin_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-----------------------------------------------------------------------------------------------------------------
-- 10. TABLE KARTU MEMBER
CREATE TABLE kartu_member (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama_member VARCHAR(100),
  email VARCHAR(100),
  nomor_member VARCHAR(50),
  tanggal_bergabung DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);