<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: user_login.php");
  exit();
}
include 'inc/koneksi.php';
$user_id = $_SESSION['user']['id'];

$sql = $conn->prepare("
  SELECT p.*, o.id AS order_id, o.nama, o.wa, o.status AS status_pesanan
  FROM pembayaran p 
  JOIN orders o ON p.order_id = o.id 
  WHERE o.user_id = ?
  ORDER BY p.tanggal_bayar DESC
");
$sql->execute([$user_id]);
$data = $sql->fetchAll(PDO::FETCH_ASSOC);
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Status Pembayaran Saya - Photostrip Maulideas</title>
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

    h2 {
      color: #d63384;
      font-weight: bold;
      text-align: center;
      margin-top: 40px;
      margin-bottom: 30px;
    }

    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }

    .badge {
      font-size: 0.85rem;
      padding: 6px 10px;
    }

    .navigasi-bawah {
      margin-top: 30px;
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 10px;
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
      <ul class="navbar-nav">
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
  <h2>Status Pembayaran Saya</h2>

  <?php if (count($data) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Kode Pesanan</th>
            <th>Nama</th>
            <th>WhatsApp</th>
            <th>Status Pesanan</th>
            <th>Metode</th>
            <th>Status Pembayaran</th>
            <th>Tanggal Bayar</th>
            <th>Bukti</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $i => $row): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= 'Order #' . htmlspecialchars($row['order_id']) ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['wa']) ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($row['status_pesanan']) ?></span></td>
              <td><?= htmlspecialchars($row['metode']) ?></td>
              <td>
                <?php
                  $statusColor = match ($row['status']) {
                    'Dikonfirmasi' => 'success',
                    'Ditolak' => 'danger',
                    default => 'warning'
                  };
                ?>
                <span class="badge bg-<?= $statusColor ?>"><?= $row['status'] ?></span>
              </td>
              <td><?= date('d-m-Y H:i', strtotime($row['tanggal_bayar'])) ?></td>
              <td>
                <?php if ($row['bukti_pembayaran']): ?>
                  <a href="uploads/bukti/<?= $row['bukti_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif ?>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">Belum ada pembayaran.</p>
  <?php endif ?>

  <!-- Tombol Navigasi Bawah -->
  <div class="navigasi-bawah">
    <a href="pengiriman.php" class="btn-pink">← Pengiriman</a>
    <a href="blog.php" class="btn-pink">Blog →</a>
    <a href="index.php" class="btn-home-round" title="Beranda">🏠</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>