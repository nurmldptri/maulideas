<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
include '../inc/navbar_admin.php';

$produk = [
  ['foto' => 'desain1.jpg', 'nama_produk' => 'Photostrip Desain 1 (5 Lembar)', 'harga' => 15000, 'stok' => 25, 'created_at' => '2025-07-01 09:00:00'],
  ['foto' => 'desain2.jpg', 'nama_produk' => 'Photostrip Desain 2 (5 Lembar)', 'harga' => 15000, 'stok' => 25, 'created_at' => '2025-07-01 09:00:00'],
  ['foto' => 'desain3.jpg', 'nama_produk' => 'Photostrip Desain 3 (5 Lembar)', 'harga' => 15000, 'stok' => 25, 'created_at' => '2025-07-01 09:00:00'],
  ['foto' => 'desain4.jpg', 'nama_produk' => 'Photostrip Desain 4 (5 Lembar)', 'harga' => 15000, 'stok' => 25, 'created_at' => '2025-07-01 09:00:00'],
  ['foto' => 'desaincombo.jpg', 'nama_produk' => 'Photostrip Combo (5 Lembar)', 'harga' => 15000, 'stok' => 25, 'created_at' => '2025-07-01 09:00:00'],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Produk - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #fff0f6, #ffe3f1);
      margin: 0;
      overflow-x: hidden;
      padding-bottom: 100px;
    }
    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin: 30px 20px 20px 20px;
      text-align: center;
    }
    .card {
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 20px rgba(214, 51, 132, 0.15);
      background-color: white;
      margin: 0 20px;
    }
    .table-responsive {
      overflow-x: auto;
    }
    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }
    .table-hover tbody tr:hover {
      background-color: #fff0f6;
    }
    .table td, .table th {
      vertical-align: middle;
      text-align: center;
      white-space: nowrap;
    }
    .table img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      border: none;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
      transition: all 0.3s ease;
    }
    .btn-pink:hover {
      background-color: #c2185b;
    }

    .btn-outline-pink {
      background-color: white;
      color: #d63384;
      border: 2px solid #d63384;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
      transition: all 0.3s ease;
    }
    .btn-outline-pink:hover {
      background-color: #ffe3f1;
      color: #c2185b;
      border-color: #c2185b;
    }

    .btn-home-circle {
      width: 56px;
      height: 56px;
      font-size: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: white;
      color: #d63384;
      border-radius: 50%;
      border: 2px solid #d63384;
      text-decoration: none;
      transition: all 0.3s ease;
      margin: 0 auto;
    }
    .btn-home-circle:hover {
      background-color: #ffe3f1;
      color: #c2185b;
    }

    @media (max-width: 768px) {
      .table td, .table th {
        font-size: 14px;
        padding: 8px;
      }
      .table img {
        width: 40px;
        height: 40px;
      }
      .btn {
        width: 100%;
        margin-bottom: 10px;
      }
      .btn-home-circle {
        width: 45px;
        height: 45px;
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="header-admin">
    <h2><i class="fa-solid fa-boxes-stacked"></i> Kelola Produk</h2>
    <p>Kelola produk yang tersedia di Photostrip Maulideas 🎁</p>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Waktu Input</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($produk as $row): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><img src="../../assets/img/<?= htmlspecialchars($row['foto']) ?>" alt="foto produk"></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td><?= $row['stok'] ?></td>
            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../pengguna/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../orders/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
      </div>

      <!-- Tombol Bulat Menu Utama -->
      <div class="text-center mt-4">
        <a href="../index.php" class="btn-home-circle" title="Menu Utama">🏠</a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>