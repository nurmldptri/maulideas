<?php
session_start();
include 'inc/koneksi.php';

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['password_confirm'] ?? '';
    $role     = $_POST['role'] ?? '';

    // Validasi
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm) || empty($role)) {
        $errors[] = "Semua field wajib diisi.";
    } elseif ($password !== $confirm) {
        $errors[] = "Konfirmasi password tidak sesuai.";
    } elseif ($role !== 'user') {
        $errors[] = "Role tidak valid. Hanya role 'user' yang diizinkan saat pendaftaran.";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email sudah digunakan.";
        } else {
            // Hash password dan simpan
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $success = $stmt->execute([$name, $username, $email, $hashedPassword, $role]);

            if ($success) {
                $_SESSION['message'] = "Pendaftaran berhasil! Silakan login.";
                header("Location: user_login.php");
                exit();
            } else {
                $errors[] = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - PhotoStrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #fff0f6; }
    .register-box {
      max-width: 500px;
      margin: 80px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(214, 51, 132, 0.1);
    }
    h2 { color: #d63384; }
    .btn-pink { background-color: #d63384; color: white; }
    .btn-pink:hover { background-color: #c2185b; }
    .text-note {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: 5px;
    }
    .text-note i {
      margin-right: 6px;
      color: #d63384;
    }
    .link-login {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    .link-login a {
      color: #d63384;
      font-weight: 500;
      text-decoration: none;
    }
    .link-login a:hover {
      text-decoration: underline;
      color: #c2185b;
    }
  </style>
</head>
<body>

<div class="register-box">
  <h2 class="text-center mb-4">Daftar Akun Baru</h2>

  <?php foreach ($errors as $e): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <form method="post" autocomplete="off">
    <div class="mb-3">
      <label>Nama Lengkap</label>
      <input type="text" name="name" required class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" required class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" required class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" required class="form-control">
    </div>
    <div class="mb-3">
      <label>Konfirmasi Password</label>
      <input type="password" name="password_confirm" required class="form-control">
    </div>
    <div class="mb-3">
      <label>Role</label>
      <select name="role" class="form-select" required>
        <option value="">-- Pilih Role --</option>
        <option value="user" selected>User</option>
        <option value="admin" disabled>Admin (Hanya oleh admin utama)</option>
      </select>
      <div class="text-note d-flex align-items-start">
        <i class="fa fa-info-circle mt-1 me-2"></i>
        <div>
          <p class="mb-1"><strong>Admin</strong> hanya dapat dibuat oleh admin utama.</p>
          <p class="mb-0">Silakan daftar sebagai <strong>user</strong>.</p>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-pink w-100">Daftar</button>
  </form>

  <div class="link-login">
    <a href="user_login.php">Login sebagai Pengguna</a>
    <a href="admin_login.php">Login sebagai Admin</a>
  </div>
</div>

</body>
</html>