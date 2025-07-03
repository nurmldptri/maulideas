<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'inc/koneksi.php'; // koneksi DB
$currentPage = basename($_SERVER['PHP_SELF']);

// Ambil semua desain dari database
$stmt = $conn->query("SELECT * FROM desain ORDER BY created_at DESC");
$desainList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>PhotoStrip Maulideas - Desain</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      text-align: center;
      margin: 40px 0 20px;
    }

    .galeri-desain {
      padding: 20px;
    }

    .card {
      border: 2px solid #ffc0cb;
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.3s ease;
      cursor: pointer;
      background-color: white;
      padding: 10px;
    }

    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 6px 12px rgba(214, 51, 132, 0.2);
    }

    .card-img-top {
      width: 100%;
      height: auto;
      object-fit: contain;
      border-radius: 10px;
    }

    .modal-content {
      background-color: transparent;
      border: none;
    }

    .modal-img {
      width: 100%;
      border-radius: 12px;
    }

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
      background-color: #c2185b;
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
    </div>
  </div>
</nav>

<!-- GALERI DESAIN -->
<section class="galeri-desain">
  <h2>Galeri Desain Photostrip</h2>
  <p class="text-center text-muted mb-4">
    📌 Klik desain untuk melihat detail. Kode desain bisa dilihat di halaman 
    <a href="index.php" class="text-decoration-underline fw-bold" style="color: #d63384;">Beranda</a>.
  </p>
  <div class="container">
    <div class="row g-4">
      <?php foreach ($desainList as $desain): ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
          <div class="card" data-bs-toggle="modal" data-bs-target="#modal<?= $desain['id'] ?>">
            <img src="assets/desain/<?= htmlspecialchars($desain['file']) ?>" alt="<?= htmlspecialchars($desain['nama']) ?>" class="card-img-top img-fluid" />
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TOMBOL NAVIGASI -->
<div class="nav-buttons">
  <a href="kartu_member.php" class="btn-pink">← Karmem</a>
  <a href="produk.php" class="btn-pink">Produk →</a>
  <a href="index.php" class="btn-home-round" title="Kembali ke Halaman Utama">🏠</a>
</div>

<!-- MODAL -->
<?php foreach ($desainList as $desain): ?>
  <div class="modal fade" id="modal<?= $desain['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img src="assets/desain/<?= htmlspecialchars($desain['file']) ?>" class="modal-img" alt="<?= htmlspecialchars($desain['nama']) ?>" />
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>