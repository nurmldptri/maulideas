<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: user_login.php");
    exit();
}
$currentPage = basename($_SERVER['PHP_SELF']);

// Data produk statis
$produk = [
  ['foto' => 'desain1.jpg', 'nama_produk' => 'Photostrip Desain 1 (5 Lembar)', 'harga' => 15000],
  ['foto' => 'desain2.jpg', 'nama_produk' => 'Photostrip Desain 2 (5 Lembar)', 'harga' => 15000],
  ['foto' => 'desain3.jpg', 'nama_produk' => 'Photostrip Desain 3 (5 Lembar)', 'harga' => 15000],
  ['foto' => 'desain4.jpg', 'nama_produk' => 'Photostrip Desain 4 (5 Lembar)', 'harga' => 15000],
  ['foto' => 'desaincombo.jpg', 'nama_produk' => 'Photostrip Desain Combo (5 Lembar)', 'harga' => 15000],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
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
    h2 {
      color: #d63384;
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
    }
    .card-produk {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(214, 51, 132, 0.1);
      transition: transform 0.3s;
    }
    .card-produk:hover {
      transform: translateY(-5px);
    }
    .card-title {
      font-size: 1rem;
      color: #d63384;
      font-weight: bold;
    }
    .btn-pesan {
      background-color: #d63384;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
    }
    .btn-pesan:hover {
      background-color: #a1003e;
    }

    /* Tombol Navigasi */
    .nav-buttons {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin: 40px auto 30px;
      max-width: 600px;
      justify-content: center;
      align-items: center;
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      border: none;
      padding: 10px 24px;
      border-radius: 10px;
      font-weight: bold;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-pink:hover {
      background-color: #a1003e;
    }

    .btn-home-round {
      background-color: white;
      border: 2px solid #d63384;
      color: #d63384;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-home-round:hover {
      background-color: #ffe3f1;
      color: #c2185b;
    }

    @media (min-width: 576px) {
      .nav-buttons {
        flex-direction: row;
      }
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
      <ul class="navbar-nav me-3">
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
    </div>
  </div>
</nav>

<!-- KONTEN PRODUK -->
<div class="container my-5">
  <h2>📦 Produk Photostrip</h2>
  <div class="row justify-content-center">
    <?php foreach ($produk as $item): ?>
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <div class="card card-produk h-100 border-0">
          <img src="assets/img/<?= htmlspecialchars($item['foto']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nama_produk']) ?>" style="height:180px; object-fit:cover;">
          <div class="card-body text-center">
            <h6 class="card-title"><?= htmlspecialchars($item['nama_produk']) ?></h6>
            <p class="card-text">Rp<?= number_format($item['harga'], 0, ',', '.') ?></p>
            <a href="pesan.php" class="btn btn-pesan btn-sm">Pesan Sekarang</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- TOMBOL NAVIGASI -->
<div class="nav-buttons">
  <a href="desain.php" class="btn-pink">← Desain</a>
  <a href="pesan.php" class="btn-pink">Pesan →</a>
  <a href="index.php" class="btn-home-round" title="Kembali ke Halaman Utama">🏠</a>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>