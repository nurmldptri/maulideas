<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'inc/koneksi.php';
$userId = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pemesanan - Photostrip Maulideas</title>
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
    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }
    .badge-status {
      font-size: 0.875rem;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 0 12px rgba(214, 51, 132, 0.1);
      margin-top: 40px;
    }
    .btn-struk {
      background-color: #d63384;
      color: white;
      border: none;
    }
    .btn-struk:hover {
      background-color: #a1003e;
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: bold;
      text-align: center;
      text-decoration: none;
      display: inline-block;
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
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href=index.php">Beranda</a></li>
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

<!-- KONTEN -->
<div class="container my-5">
  <div class="card">
    <h2>Riwayat Pemesanan Anda</h2>
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Whatsapp</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Struk</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($orders) === 0): ?>
            <tr><td colspan="7" class="text-center text-muted">Belum ada pesanan.</td></tr>
          <?php else: ?>
            <?php $no = 1; foreach ($orders as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><a href="https://wa.me/<?= htmlspecialchars($row['wa']) ?>" target="_blank"><?= htmlspecialchars($row['wa']) ?></a></td>
                <td><span class="badge bg-light text-dark"><?= $row['metode'] ?></span></td>
                <td>
                  <span class="badge bg-<?= 
                    $row['status'] == 'Selesai' ? 'success' : 
                    ($row['status'] == 'Diproses' ? 'warning' : 
                    ($row['status'] == 'Batal' ? 'danger' : 'secondary')) ?> badge-status">
                    <?= $row['status'] ?>
                  </span>
                </td>
                <td>
                  <?php if ($row['status'] === 'Selesai') : ?>
                    <a href="struk.php?id=<?= $row['id'] ?>" class="btn btn-struk btn-sm" target="_blank">🧾 Struk</a>
                  <?php else: ?>
                    <span class="text-muted">Belum tersedia</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Navigasi Bawah -->
    <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 mt-4">
      <a href="pesan.php" class="btn btn-pink w-100 w-sm-auto">← Pesan</a>
      <a href="pengiriman.php" class="btn btn-pink w-100 w-sm-auto">Pengiriman →</a>
      <a href="index.php" class="btn-home-round" title="Beranda">🏠</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>