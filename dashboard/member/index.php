<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$query = $conn->query("SELECT km.*, u.username FROM kartu_member km JOIN users u ON km.user_id = u.id ORDER BY km.tanggal_bergabung DESC");
$data = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Kartu Member - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #fff0f6, #ffe3f1);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 100px;
    }
    .header-admin {
      background-color: #d63384;
      color: white;
      padding: 30px 20px;
      text-align: center;
      border-radius: 12px;
      margin: 30px auto 20px;
      box-shadow: 0 0 10px rgba(214, 51, 132, 0.2);
    }
    .card {
      background-color: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 0 12px rgba(214, 51, 132, 0.1);
      margin: 0 20px;
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
    .btn-edit {
      background-color: #d63384;
      color: white;
      border-radius: 8px;
      padding: 6px 14px;
      font-weight: bold;
      font-size: 0.9rem;
      text-decoration: none;
    }
    .btn-edit:hover {
      background-color: #c2185b;
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
  <div class="header-admin">
    <h2>💳 Edit Kartu Member</h2>
    <p class="mb-0">Daftar member yang telah terdaftar secara otomatis oleh sistem</p>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Member</th>
            <th>Email</th>
            <th>No. Member</th>
            <th>Tanggal Bergabung</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($data) === 0): ?>
            <tr><td colspan="7" class="text-muted text-center">Belum ada data member.</td></tr>
          <?php else: ?>
            <?php $no = 1; foreach ($data as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['nama_member']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['nomor_member']) ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_bergabung'])) ?></td>
                <td>
                  <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                </td>
              </tr>
            <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Navigasi -->
    <div class="container text-center my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <a href="../pelanggan/index.php" class="btn btn-outline-pink w-100 w-md-auto">&larr; Kembali</a>
        <a href="../pengguna/index.php" class="btn btn-pink w-100 w-md-auto">Selanjutnya &rarr;</a>
      </div>

      <div class="text-center mt-4">
        <a href="../index.php" class="btn-home-circle" title="Kembali ke Halaman Utama">🏠</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
