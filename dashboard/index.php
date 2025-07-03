<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

include '../inc/koneksi.php';
include 'inc/navbar_admin.php';
$userCount = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$orderCount = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ffe3f1, #fff0f6);
      margin: 0;
      padding-bottom: 80px;
    }

    .btn-logout {
      background-color: #f8c7dc;
      color: #d63384;
      font-weight: bold;
      padding: 8px 18px;
      border: 2px solid #f5b4d1;
      border-radius: 10px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-logout:hover {
      background-color: #d63384;
      color: white;
    }

    .logo-title {
      text-align: center;
      margin-top: 10px;
    }

    .logo-title img {
      width: 80px;
      height: 80px;
      border-radius: 12px;
      object-fit: cover;
    }

    .logo-title h1 {
      font-size: 28px;
      color: #d63384;
      font-weight: bold;
      margin-top: 10px;
    }

    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 30px 20px;
      border-radius: 12px;
      text-align: center;
      margin: 30px 20px;
    }

    .admin-link {
      background-color: #d63384;
      color: white;
      padding: 12px 25px;
      border-radius: 10px;
      font-weight: bold;
      text-decoration: none;
      transition: background-color 0.3s ease;
      display: inline-block;
      margin: 8px;
    }

    .admin-link:hover {
      background-color: #c2185b;
      color: white;
    }

    .admin-shortcuts {
      text-align: center;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .stats {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
      margin: 30px 0;
    }

    .stat-box {
      background-color: white;
      border-left: 8px solid #ff69b4;
      padding: 20px 25px;
      border-radius: 12px;
      min-width: 220px;
      box-shadow: 0 2px 10px rgba(214, 51, 132, 0.1);
      transition: 0.3s ease;
    }

    .stat-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 20px rgba(214, 51, 132, 0.2);
    }

    .stat-box h3 {
      margin: 0;
      color: #d63384;
      font-size: 24px;
    }

    .stat-box p {
      margin: 5px 0 0;
      color: #666;
    }

    footer {
      background-color: #ffe3f1;
      color: #d63384;
      text-align: center;
      padding: 16px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }

    @media (max-width: 768px) {
      .stats {
        flex-direction: column;
        align-items: center;
      }

      .stat-box {
        width: 80%;
      }

      .admin-link {
        display: block;
        margin: 10px auto;
        width: 90%;
      }

      .btn-logout {
        display: block;
        margin: 10px auto 0;
        text-align: center;
      }
    }
  </style>
</head>
<body>

<!-- Tombol Logout -->
<div class="text-end p-3">
  <a href="../logout.php" class="btn-logout me-3">
    <i class="fas fa-sign-out-alt"></i> Keluar
  </a>
</div>

<!-- ✅ Logo dan Judul -->
<div class="logo-title">
  <img src="../assets/img/logo_maulideas.jpg" alt="Logo Photostrip">
  <h1>Photostrip Maulideas</h1>
</div>

<!-- ✅ Header -->
<div class="container my-4">
  <div class="header-admin">
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?>!</h2>
    <p>Laporan Login Admin, Data Pelanggan, Edit Kartu Member, Kelola Pengguna, Kelola Produk, Kelola Pesanan, Kelola Pengiriman, Kelola Pembayaran dan Kelola Blog di sistem Photostrip Maulideas 💼</p>
  </div>

  <!-- ✅ Admin Quick Access -->
  <div class="admin-shortcuts">
    <a href="laporan/admin.php" class="admin-link">🔐 Laporan Login Admin</a>
    <a href="pelanggan/index.php" class="admin-link">🙍 Data Pelanggan</a>
    <a href="member/index.php" class="admin-link">✏️ Edit Kartu Member</a>
    <a href="pengguna/index.php" class="admin-link">👥 Kelola Pengguna</a>
    <a href="produk/index.php" class="admin-link">📦 Kelola Produk</a>
    <a href="orders/index.php" class="admin-link">📋 Kelola Pesanan</a>
    <a href="pengiriman/index.php" class="admin-link">🚚 Kelola Pengiriman</a>
    <a href="pembayaran/index.php" class="admin-link">💳 Kelola Pembayaran</a>
    <a href="blog/index.php" class="admin-link">📝 Kelola Blog</a>

  </div>

  <!-- ✅ Statistik -->
  <div class="stats">
    <div class="stat-box text-center">
      <h3><?= $userCount ?></h3>
      <p><i class="fas fa-users"></i> Total Pengguna</p>
    </div>
    <div class="stat-box text-center">
      <h3><?= $orderCount ?></h3>
      <p><i class="fas fa-shopping-cart"></i> Total Pesanan</p>
    </div>
  </div>
</div>

<!-- ✅ Footer -->
<footer>
  <p class="mb-0">&copy; 2025 Photostrip Maulideas. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
