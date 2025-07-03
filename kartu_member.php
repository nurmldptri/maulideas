<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
include 'inc/koneksi.php';

$userId = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT * FROM kartu_member WHERE user_id = ?");
$stmt->execute([$userId]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    $nomor_member = "MLD" . str_pad($userId, 5, '0', STR_PAD_LEFT);
    $tanggal = date("Y-m-d H:i:s");

    $insert = $conn->prepare("INSERT INTO kartu_member (user_id, nama_member, email, nomor_member, tanggal_bergabung) VALUES (?, ?, ?, ?, ?)");
    $insert->execute([
        $userId,
        $_SESSION['user']['username'],
        $_SESSION['user']['email'],
        $nomor_member,
        $tanggal
    ]);

    $stmt->execute([$userId]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Member - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 70px;
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

    .card-member {
      max-width: 600px;
      margin: auto;
      background: linear-gradient(135deg, #ffe3f1, #fff0f6);
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(214, 51, 132, 0.15);
      text-align: left;
      position: relative;
    }
    h2 {
      color: #d63384;
      font-weight: bold;
      text-align: center;
      margin-bottom: 25px;
    }
    .info {
      font-size: 1.1em;
      margin-bottom: 12px;
      padding-left: 20px;
    }
    .label {
      font-weight: bold;
      color: #d63384;
      width: 130px;
      display: inline-block;
    }
    .qr-code {
      position: absolute;
      bottom: 30px;
      right: 30px;
      width: 90px;
      height: 90px;
      background-image: url('https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=<?= $member["nomor_member"] ?>');
      background-size: cover;
      border-radius: 10px;
    }

    .btn-custom {
      background-color: #d63384;
      color: white;
      font-weight: bold;
      padding: 10px 24px;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      margin: 5px;
    }
    .btn-custom:hover {
      background-color: #c2185b;
    }

    .btn-round-home {
      background-color: #fff;
      border: 2px solid #d63384;
      color: #d63384;
      font-size: 20px;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s ease;
      margin: 5px auto;
    }
    .btn-round-home:hover {
      background-color: #ffe3f1;
      color: #c2185b;
    }

    .btn-container {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
    }
    @media (min-width: 576px) {
      .btn-container {
        flex-direction: row;
        justify-content: center;
      }
    }

    @media print {
      body {
        background: white !important;
      }
      .navbar,
      .btn-container {
        display: none;
      }
      .qr-code {
        position: static;
        float: right;
      }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
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

<!-- KARTU MEMBER -->
<div class="card-member">
  <h2>💳 Kartu Member Photostrip Maulideas</h2>

  <div class="info"><span class="label">Nama</span>: <?= htmlspecialchars($member['nama_member']) ?></div>
  <div class="info"><span class="label">Email</span>: <?= htmlspecialchars($member['email']) ?></div>
  <div class="info"><span class="label">No. Member</span>: <?= htmlspecialchars($member['nomor_member']) ?></div>
  <div class="info"><span class="label">Bergabung</span>: <?= date('d M Y, H:i', strtotime($member['tanggal_bergabung'])) ?></div>

  <div class="qr-code"></div>
</div>

<!-- Navigasi -->
<div class="btn-container">
  <a href="pelanggan_form.php" class="btn-custom">← Pribadi</a>
  <a href="desain.php" class="btn-custom">Desain →</a>
  <button onclick="window.print()" class="btn-custom">🖨️ Cetak Kartu</button>
</div>

<div class="text-center mt-3">
  <a href="index.php" class="btn-round-home" title="Beranda">🏠</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>