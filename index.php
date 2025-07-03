<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: user_login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$userName = $_SESSION['user']['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff0f6;
    }

    .navbar {
      background-color: #ffe3f1;
      padding: 10px 20px;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: bold;
      color: #d63384 !important;
    }

    .navbar-brand img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
    }

    .navbar-nav .nav-link {
      background-color: #f8c7dc;
      color: #d63384 !important;
      font-weight: bold;
      margin: 4px;
      padding: 8px 16px;
      border-radius: 10px;
      border: 2px solid #f5b4d1;
      transition: all 0.3s ease;
      text-align: center;
    }

    .navbar-nav .nav-link:hover {
      background-color: #d63384;
      color: white !important;
    }

    .navbar-nav .nav-link.active {
      background-color: #d63384 !important;
      color: white !important;
      border-color: #d63384;
    }

    .judul-home {
      text-align: center;
      margin-top: 40px;
      margin-bottom: 30px;
    }

    .judul-home h1 {
      font-family: 'Pacifico', cursive;
      font-size: 48px;
      color: #d63384;
    }

    .welcome-text {
      font-size: 24px;
      font-weight: bold;
      color: #d63384;
    }

    .user-info {
      font-size: 14px;
      color: #888;
      margin-bottom: 20px;
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .btn-pink:hover {
      background-color: #c2185b;
    }

    .desain-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 30px;
    }

    .desain-item {
      width: 140px;
      border: 2px solid #ffc0cb;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .desain-item img {
      width: 100%;
      height: auto;
      display: block;
      border-radius: 6px;
    }

    footer {
      background-color: #ffe3f1;
      color: #d63384;
      padding: 30px 0;
      margin-top: 50px;
    }

    footer a {
      color: #c2185b;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="assets/img/logo_maulideas.jpg" alt="Logo">
      Photostrip Maulideas
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navmenu">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pelanggan_form.php' ? 'active' : '' ?>" href="pelanggan_form.php">Pribadi</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'kartu_member.php' ? 'active' : '' ?>" kartu_member.php">Karmem</a></li> 
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'desain.php' ? 'active' : '' ?>" href="desain.php">Desain</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'produk.php' ? 'active' : '' ?>" href="produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pesan.php' ? 'active' : '' ?>" href="pesan.php">Pesan</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'riwayat.php' ? 'active' : '' ?>" href="riwayat.php">Riwayat</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pengiriman.php' ? 'active' : '' ?>" href="pengiriman.php">Pengiriman</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pembayaran.php' ? 'active' : '' ?>" href="pembayaran.php">Pembayaran</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'blog.php' ? 'active' : '' ?>" href="blog.php">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Keluar</a></li>
      </ul>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten Utama -->
<div class="container">
  <div class="judul-home">
    <h1>Hai, <?= htmlspecialchars($userName) ?>! 👋</h1>
    <p class="welcome-text">Selamat datang di Photostrip Maulideas 💖</p>
    <p class="user-info">Login sebagai: <strong><?= htmlspecialchars($userName) ?></strong></p>
    <div class="alert alert-warning mx-auto" style="max-width: 600px;">
      🔥 <strong>Promo Spesial Bulan Ini!</strong><br>
      Dapatkan <strong>diskon 10%</strong> jika order photostrip di atas Rp. 50.000 🎁
    </div>
  </div>

  <section class="text-center">
    <h2 style="color: #d63384;">Kode Desain Photostrip Maulideas</h2>
    <div class="desain-grid">
      <div class="desain-item"><img src="assets/desain/desain1.jpg" alt="Desain 1"></div>
      <div class="desain-item"><img src="assets/desain/desain2.jpg" alt="Desain 2"></div>
      <div class="desain-item"><img src="assets/desain/desain3.jpg" alt="Desain 3"></div>
      <div class="desain-item"><img src="assets/desain/desain4.jpg" alt="Desain 4"></div>
    </div>
    <div class="my-4">
      <a href="pesan.php" class="btn-pink">Pesan Sekarang</a>
    </div>
  </section>

  <section class="container my-5">
    <div class="card p-4 shadow-sm">
      <h2 class="text-center mb-3" style="color: #d63384;">Tentang Photostript Maulideas</h2>
      <p><strong>Website :</strong> Photostript Maulideas</p>
      <p><strong>Alamat  :</strong> JL Zamrud 10, RT 48 NO 26, Berbas Tengah</p>
      <div class="mb-3">
        <iframe 
          src="https://www.google.com/maps?q=-0.155291,117.496456&hl=id&z=17&output=embed"
          width="100%" 
          height="250" 
          style="border:1px solid #f3c6db; border-radius: 10px;" 
          allowfullscreen 
          loading="lazy">
        </iframe>
      </div>
      <p><strong>Detail Informasi Produk:</strong></p>
      <ul>
        <li>Ukuran photostript: 5x15 cm</li>
        <li>Harga: Rp 15.000,- / 8 lembar</li>
        <li>Setiap desain memiliki kode untuk memudahkan pemesanan.</li>
      </ul>
    </div>
  </section>
</div>

<!-- Footer -->
<footer>
  <div class="container text-center">
    <h5 class="mb-3">📌 Hubungi Kami</h5>
    <p>
      <strong>WhatsApp:</strong> 
      <a href="https://wa.me/62895327994255" target="_blank">0895327994255 (Maulida)</a><br>
      <strong>Instagram:</strong> 
      <a href="https://instagram.com/maulideas" target="_blank">@maulideas</a><br>
      <strong>Email:</strong> 
      <a href="mailto:maulideas30@gmail.com">maulideas30@gmail.com</a>
    </p>
    <hr style="border-top: 1px solid #f0bcd1;">
    <p class="mb-0">&copy; 2025 Photostript Maulideas. All rights reserved.</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>