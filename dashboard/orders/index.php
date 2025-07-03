<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';
include '../inc/navbar_admin.php';

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pesanan - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #fff0f6, #ffe3f1);
      margin: 0;
      padding-bottom: 100px;
    }

    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
    }

    .btn-back, .btn-pink, .btn-outline-pink {
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
      transition: 0.3s;
    }

    .btn-back {
      background-color: #f8c7dc;
      color: #d63384;
      border: 2px solid #f5b4d1;
    }
    .btn-back:hover {
      background-color: #d63384;
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

    .card {
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 20px rgba(214, 51, 132, 0.15);
      background-color: white;
      overflow-x: auto;
    }

    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }

    .table td, .table th {
      vertical-align: middle;
      text-align: center;
      white-space: nowrap;
    }

    .btn-info, .btn-warning, .btn-danger {
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

    .badge-success { background-color: #d4edda; color: #155724; }
    .badge-warning { background-color: #fff3cd; color: #856404; }
    .badge-danger { background-color: #f8d7da; color: #721c24; }
    .badge-secondary { background-color: #e2e3e5; color: #383d41; }

    .bukti-link {
      font-size: 0.875rem;
    }

    .table-hover tbody tr:hover {
      background-color: #fff0f6;
    }

    @media (max-width: 768px) {
      .card {
        padding: 15px;
      }
      .table-responsive {
        overflow-x: auto;
      }
      .btn {
        font-size: 0.85rem;
        padding: 6px 10px;
      }
    }
  </style>
</head>
<body>

<div class="container my-4">
  <div class="header-admin">
    <h2><i class="fa-solid fa-clipboard-list"></i> Kelola Pesanan</h2>
    <p>Kelola semua pesanan dengan cepat dan mudah 💌</p>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>WhatsApp</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Bukti</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $orders->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><a href="https://wa.me/<?= htmlspecialchars($row['wa']) ?>" target="_blank"><?= htmlspecialchars($row['wa']) ?></a></td>
            <td><span class="badge bg-light text-dark"><?= htmlspecialchars($row['metode']) ?></span></td>
            <td>
              <span class="badge badge-<?= 
                $row['status'] == 'Selesai' ? 'success' :
                ($row['status'] == 'Diproses' ? 'warning' :
                ($row['status'] == 'Batal' ? 'danger' : 'secondary')) ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
            <td>
              <?php if (!empty($row['bukti'])) : ?>
                <a class="btn btn-sm btn-outline-primary bukti-link" href="../../uploads/<?= htmlspecialchars($row['bukti']) ?>" target="_blank">Lihat</a>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Detail</a>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../produk/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../pengiriman/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
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