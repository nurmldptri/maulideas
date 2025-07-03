<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';
include '../inc/navbar_admin.php';

$sql = $conn->prepare("
  SELECT p.*, u.name AS nama_user, o.nama AS nama_pemesan, o.status AS status_pesanan
  FROM pembayaran p
  JOIN orders o ON p.order_id = o.id
  JOIN users u ON o.user_id = u.id
  ORDER BY p.tanggal_bayar DESC
");
$sql->execute();
$data = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pembayaran - Admin</title>
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
    .table td, .table th {
      text-align: center;
      vertical-align: middle;
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
    .badge {
      font-size: 0.8rem;
      padding: 6px 10px;
      border-radius: 20px;
    }
    .badge-success {
      background-color: #c7f3d4;
      color: #0f5132;
    }
    .badge-warning {
      background-color: #fff3cd;
      color: #664d03;
    }
    .badge-danger {
      background-color: #f8d7da;
      color: #842029;
    }
    .badge-secondary {
      background-color: #e2e3e5;
      color: #41464b;
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
    <h2><i class="fas fa-credit-card"></i> Kelola Pembayaran</h2>
    <p>Kelola bukti pembayaran pesanan pengguna 💳</p>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Order</th>
            <th>Pemesan</th>
            <th>User</th>
            <th>Metode</th>
            <th>Status Bayar</th>
            <th>Status Pesanan</th>
            <th>Tanggal</th>
            <th>Bukti</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $i => $row): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>#<?= $row['order_id'] ?></td>
            <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
            <td><?= htmlspecialchars($row['nama_user']) ?></td>
            <td><?= htmlspecialchars($row['metode']) ?></td>
            <td>
              <span class="badge badge-<?= $row['status'] == 'Dikonfirmasi' ? 'success' : ($row['status'] == 'Ditolak' ? 'danger' : 'warning') ?>">
                <?= $row['status'] ?>
              </span>
            </td>
            <td><span class="badge badge-secondary"><?= $row['status_pesanan'] ?></span></td>
            <td><?= date('d-m-Y H:i', strtotime($row['tanggal_bayar'])) ?></td>
            <td>
              <?php if ($row['bukti_pembayaran']) : ?>
                <a href="../../uploads/bukti/<?= $row['bukti_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
              <?php else : ?>
                <span class="text-muted">-</span>
              <?php endif ?>
            </td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="konfirmasi.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-check"></i></a>
                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endforeach ?>
          <?php if (empty($data)) : ?>
          <tr><td colspan="10" class="text-center"><em>Belum ada data pembayaran.</em></td></tr>
          <?php endif ?>
        </tbody>
      </table>
    </div>

<!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../pengiriman/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../blog/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
      </div>

      <div class="text-center mt-4">
        <a href="../index.php" class="btn-home-circle" title="Menu Utama">🏠</a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>