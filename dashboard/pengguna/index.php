<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';
include '../inc/navbar_admin.php'; // ✅ Include navbar admin

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pengguna - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #fff0f6, #ffe3f1);
    }
    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
    }
    .card {
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 0 20px rgba(214, 51, 132, 0.15);
      background-color: white;
    }
    .table thead {
      background-color: #ffd6e7;
      color: #d63384;
    }
    .table-hover tbody tr:hover {
      background-color: #fff0f6;
      transition: 0.3s ease;
    }
    .table td, .table th {
      vertical-align: middle;
      text-align: center;
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
    .btn-back, .btn-pink, .btn-outline-pink {
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 20px;
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
      .table-responsive {
        overflow-x: auto;
      }
      .btn {
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>

<div class="container my-4">
  <div class="header-admin">
    <h2><i class="fa-solid fa-users"></i> Kelola Pengguna</h2>
    <p>Kelola akun pengguna yang terdaftar dengan mudah 💻</p>
  </div>

  <div class="card">
    <div class="text-end mb-3">
      <a href="tambah.php" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Pengguna</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Waktu Daftar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($user = $users->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($user['name'] ?: '🙈 (Tanpa Nama)') ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
              <?php
                $emoji = $user['role'] == 'admin' ? '🛡️' : '👤';
                echo $emoji . ' ' . ucfirst(htmlspecialchars($user['role']));
              ?>
            </td>
            <td><?= date('d-m-Y H:i', strtotime($user['created_at'])) ?></td>
            <td>
              <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="hapus.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin nih mau hapus pengguna ini? 😭')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php if ($users->rowCount() === 0) : ?>
          <tr>
            <td colspan="7" class="text-center"><em>Belum ada data pengguna.</em></td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../member/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../produk/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
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