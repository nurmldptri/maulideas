<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if (empty($name) || empty($username) || empty($email) || empty($role)) {
        $error = "Semua kolom wajib diisi.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, role=? WHERE id=?");
        $stmt->execute([$name, $username, $email, $role, $id]);
        $success = "✅ Pengguna berhasil diperbarui!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengguna - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #fff0f6, #ffe3f1);
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
      transition: 0.3s ease;
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
      max-width: 600px;
      margin: auto;
    }
    .btn-pink {
      background-color: #d63384;
      color: white;
      font-weight: bold;
    }
    .btn-pink:hover {
      background-color: #c2185b;
      color: white;
    }
  </style>
</head>
<body>

<!-- ✅ NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php">
      <img src="../../assets/img/logo_maulideas.jpg" alt="Logo">
      Photostrip Maulideas
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="../index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="../orders/index.php">Kelola Pesanan</a></li>
        <li class="nav-item"><a class="nav-link" href="blog/index.php">Blog</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php">Pengguna</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            👤 <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item text-danger" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ HEADER -->
<div class="container my-4">
  <div class="header-admin">
    <h2><i class="fa-solid fa-user-pen"></i> Edit Data Pengguna</h2>
    <p>Perbarui data akun pengguna sistem 🛠️</p>
  </div>

  <div class="card">
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Nama</label>
        <input name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Username</label>
        <input name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select" required>
          <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
        </select>
      </div>
      <button type="submit" class="btn btn-pink w-100">Simpan Perubahan</button>
      <a href="index.php" class="btn btn-secondary w-100 mt-2">← Kembali ke Daftar Pengguna</a>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
