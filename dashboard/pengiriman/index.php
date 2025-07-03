<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

include '../../inc/koneksi.php';
include '../inc/navbar_admin.php';

$sql = $conn->prepare("
  SELECT pengiriman.*, users.name AS nama_pemesan, orders.alamat, orders.rincian
  FROM pengiriman
  JOIN orders ON pengiriman.order_id = orders.id
  JOIN users ON orders.user_id = users.id
  ORDER BY pengiriman.tanggal_kirim DESC
");
$sql->execute();
$data = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pengiriman - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #fff0f6, #ffe3f1);
      padding-bottom: 100px;
    }
    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 30px;
      text-align: center;
      border-radius: 12px;
      margin: 30px 20px 20px 20px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(214, 51, 132, 0.1);
      margin: 0 20px;
    }
    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }
    .table-hover tbody tr:hover {
      background-color: #fff0f6;
    }
    .btn-pink {
      background-color: #d63384;
      color: white;
      font-weight: bold;
      border: none;
      padding: 10px 24px;
      border-radius: 10px;
    }
    .btn-pink:hover {
      background-color: #c2185b;
      color: white;
    }
    .btn-outline-pink {
      background-color: white;
      color: #d63384;
      border: 2px solid #d63384;
      font-weight: bold;
      padding: 10px 24px;
      border-radius: 10px;
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
      margin: 0 auto;
      transition: 0.3s ease;
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
    .badge-info {
      background-color: #d0ebff;
      color: #055160;
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
  <div class="header-admin shadow-sm">
    <h2><i class="fas fa-truck"></i> Kelola Pengiriman</h2>
    <p>Daftar semua pengiriman pesanan pengguna 🚚</p>
    <a href="tambah.php" class="btn btn-outline-light mt-3">
      <i class="fas fa-plus"></i> Tambah Pengiriman
    </a>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama Pemesan</th>
            <th>Kurir</th>
            <th>Resi</th>
            <th>Status</th>
            <th>Tanggal Kirim</th>
            <th>Alamat</th>
            <th>Rincian</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($data) > 0): ?>
            <?php foreach ($data as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
                <td><?= $row['kurir'] ?: '<em>-</em>' ?></td>
                <td><?= $row['resi'] ?: '<em>-</em>' ?></td>
                <td>
                  <span class="badge badge-<?= 
                    $row['status'] == 'Diterima' ? 'success' :
                    ($row['status'] == 'Dikirim' ? 'info' : 'secondary')
                  ?>">
                    <?= $row['status'] ?>
                  </span>
                </td>
                <td><?= $row['tanggal_kirim'] ? date('d-m-Y H:i', strtotime($row['tanggal_kirim'])) : '-' ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?: '<em>-</em>' ?></td>
                <td><?= htmlspecialchars($row['rincian']) ?: '<em>-</em>' ?></td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-pink">Edit</a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-pink" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                  </div>
                </td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr><td colspan="9"><em>Belum ada data pengiriman.</em></td></tr>
          <?php endif ?>
        </tbody>
      </table>
    </div>

    <!-- Navigasi bawah -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../orders/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../pembayaran/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
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