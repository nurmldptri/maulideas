<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';
include '../inc/navbar_admin.php';

$blogs = $conn->query("SELECT b.*, u.username FROM blogs b JOIN users u ON b.author_id = u.id ORDER BY b.created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Blog - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
    }
    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff0f6, #ffe3f1);
    }
    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 30px 20px;
      text-align: center;
      border-radius: 12px;
    }
    .card-container {
      padding: 20px;
      max-width: 100%;
    }
    .card {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 20px rgba(214, 51, 132, 0.15);
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
    }
    .table td.title-column {
      max-width: 250px;
      word-wrap: break-word;
      white-space: normal;
    }
    .btn-info, .btn-warning, .btn-danger, .btn-back {
      border: none;
      font-weight: bold;
    }
    .btn-info {
      background-color: #f8c7dc;
      color: #d63384;
    }
    .btn-info:hover {
      background-color: #d63384;
      color: white;
    }
    .btn-warning {
      background-color: #ffe8ef;
      color: #d63384;
    }
    .btn-warning:hover {
      background-color: #f6b7d0;
      color: white;
    }
    .btn-danger {
      background-color: #f5b4d1;
      color: #a1003e;
    }
    .btn-danger:hover {
      background-color: #c2185b;
      color: white;
    }
    .btn-back {
      background-color: #ffe3f1;
      color: #d63384;
      border: 1px solid #f5b4d1;
    }
    .btn-back:hover {
      background-color: #f8c7dc;
      color: white;
    }
    .btn-pink {
      background-color: #d63384;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
      border: none;
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
      font-size: 24px;
      display: inline-block;
      padding: 12px 18px;
      background-color: white;
      color: #d63384;
      border-radius: 50%;
      border: 2px solid #d63384;
      text-decoration: none;
      transition: all 0.3s ease;
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
      .table td.title-column {
        max-width: 180px;
      }
    }
  </style>
</head>
<body>

<div class="container my-4">
  <div class="header-admin shadow-sm py-4 px-3">
    <h2 class="mb-1"><i class="fa-solid fa-pen-nib"></i> Kelola Blog</h2>
    <p class="mb-0">Kelola tulisan inspiratifmu di sini ✍️</p>
  </div>
</div>

<div class="card-container">
  <div class="card">
    <div class="text-end mb-3">
      <a href="tambah.php" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Artikel</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $blogs->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr>
            <td class="title-column"><strong><?= htmlspecialchars($row['title']) ?></strong></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
            <td>
              <div class="d-grid gap-1">
                <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm w-100">Lihat</a>
                <div class="d-flex justify-content-center gap-1 mt-1">
                  <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus artikel ini?')">Hapus</a>
                </div>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php if ($blogs->rowCount() === 0): ?>
          <tr>
            <td colspan="4" class="text-center"><em>Belum ada artikel blog.</em></td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

<!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../pembayaran/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../laporan/admin.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
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