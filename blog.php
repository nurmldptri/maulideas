<?php
session_start();
include 'inc/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->query("SELECT b.*, u.username as author FROM blogs b JOIN users u ON b.author_id = u.id ORDER BY created_at DESC");
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Blog - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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

    .section-wrapper {
      padding: 30px 15px;
    }

    .section-title {
      text-align: center;
      color: #d63384;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .card {
      border: 2px solid #ffc0cb;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(214, 51, 132, 0.1);
      transition: transform 0.3s ease;
      background-color: #fff;
    }

    .card:hover {
      transform: scale(1.02);
    }

    .card-title {
      color: #d63384;
      font-weight: bold;
    }

    .card-text {
      font-size: 15px;
      color: #555;
    }

    .btn-outline-danger {
      border-color: #d63384;
      color: #d63384;
    }

    .btn-outline-danger:hover {
      background-color: #d63384;
      color: #fff;
    }

    .navigasi-bawah {
      margin-top: 40px;
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    @media (min-width: 576px) {
      .navigasi-bawah {
        flex-direction: row;
        justify-content: center;
        align-items: center;
      }
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-pink:hover {
      background-color: #c2185b;
      color: white;
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

    @media (max-width: 576px) {
      .card-text {
        font-size: 14px;
      }

      .navbar-brand img {
        width: 40px;
        height: 40px;
      }

      .btn-home-round {
        width: 45px;
        height: 45px;
        font-size: 20px;
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

<!-- BLOG CONTENT -->
<div class="container section-wrapper">
  <h2 class="section-title">Artikel Photostrip Maulideas</h2>
  <div class="row">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <div class="col-lg-4 col-md-6 mb-4"> 
        <div class="card h-100 p-3">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
            <p class="card-text"><?= substr(strip_tags($row['content']), 0, 100) ?>...</p>
            <a href="blog_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger mt-2">Baca Selengkapnya</a>
          </div>
          <div class="card-footer bg-white border-0">
            <small class="text-muted">Ditulis oleh <?= $row['author'] ?> pada <?= date('d M Y', strtotime($row['created_at'])) ?></small>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Tombol Navigasi -->
  <div class="navigasi-bawah mt-5">
    <a href="pembayaran.php" class="btn-pink">← Pembayaran</a>
    <a href="pelanggan_form.php" class="btn-pink">Lanjut ke Data Pribadi →</a>
    <a href="index.php" class="btn-home-round" title="Beranda">🏠</a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>