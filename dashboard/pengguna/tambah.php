<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = "Semua field wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email sudah digunakan.";
        } else {
            $hashed = hash('sha256', $password);
            $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed, $role]);
            $success = "✅ Pengguna berhasil ditambahkan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- RESPONSIVE -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 0 15px rgba(214, 51, 132, 0.1);
      background: white;
    }
    h3 {
      color: #d63384;
      font-weight: bold;
      text-align: center;
      margin-bottom: 25px;
    }
    label {
      color: #d63384;
      font-weight: 500;
    }
    .form-control:focus {
      border-color: #d63384;
      box-shadow: 0 0 0 0.2rem rgba(214, 51, 132, 0.2);
    }
    .btn-pink {
      background-color: #d63384;
      color: white;
      font-weight: bold;
    }
    .btn-pink:hover {
      background-color: #c2185b;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
      <div class="card">
        <h3>➕ Tambah Pengguna</h3>

        <?php if ($error): ?>
          <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert alert-success text-center"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
              <option value="">-- Pilih Role --</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
          <button type="submit" class="btn btn-pink w-100">Simpan</button>
          <a href="index.php" class="btn btn-secondary w-100 mt-2">← Kembali</a>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>