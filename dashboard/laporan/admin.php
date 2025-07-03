<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';
include '../inc/navbar_admin.php';

$query = $conn->query("SELECT * FROM laporan_admin ORDER BY waktu_login DESC");
$data = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>📊 Laporan Login Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #fff0f6, #ffe3f1);
      font-family: 'Segoe UI', sans-serif;
      overflow-x: hidden;
    }
    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      margin-bottom: 25px;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 15px rgba(214, 51, 132, 0.1);
    }
    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }
    .table-hover tbody tr:hover {
      background-color: #fff0f6;
    }
    .table th, .table td {
      text-align: center;
      vertical-align: middle;
    }
    .no-data {
      font-style: italic;
      color: #999;
    }
    .btn {
      font-weight: bold;
      border-radius: 10px;
      padding: 8px 16px;
      transition: all 0.3s ease;
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
      border: none;
    }
    .btn-pink:hover {
      background-color: #c2185b;
    }
    .btn-outline-pink {
      background-color: white;
      color: #d63384;
      border: 2px solid #d63384;
    }
    .btn-outline-pink:hover {
      background-color: #ffe3f1;
      color: #c2185b;
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
      .table th, .table td {
        font-size: 13px;
        padding: 6px;
      }
      .btn {
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>

<div class="container py-4">
  <div class="header-admin shadow-sm">
    <h2><i class="fas fa-user-shield"></i> Laporan Login Admin</h2>
    <p>Riwayat aktivitas login dari para admin 👩‍💻</p>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Admin</th>
            <th>Waktu Login</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($data) === 0): ?>
            <tr><td colspan="3" class="no-data">Belum ada aktivitas login.</td></tr>
          <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['nama_admin']) ?></td>
                <td><?= date('d-m-Y H:i:s', strtotime($row['waktu_login'])) ?></td>
              </tr>
            <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../blog/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../pelanggan/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
      </div>

      <div class="text-center mt-4">
        <a href="../index.php" class="btn-home-circle" title="Kembali ke Dashboard">🏠</a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
