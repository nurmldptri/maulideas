<?php
session_start();
include 'inc/koneksi.php';

$error = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $passwordInput = trim($_POST['password']);
    $passwordHashed = hash('sha256', $passwordInput);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] === $passwordHashed) {
        if ($user['role'] !== 'admin') {
            $error = "Akses ditolak. Hanya admin yang dapat login di sini.";
        } else {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'] ?? '',
                'email' => $user['email'],
                'role' => $user['role']
            ];

            // Catat laporan login admin
            $insert = $conn->prepare("INSERT INTO laporan_admin (admin_id, nama_admin, waktu_login) VALUES (?, ?, NOW())");
            $insert->execute([$user['id'], $user['name']]);

            header("Location: dashboard/index.php");
            exit();
        }
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
    }
    .login-box {
      max-width: 400px;
      margin: 100px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(214, 51, 132, 0.1);
    }
    h2 {
      color: #d63384;
    }
    .btn-pink {
      background-color: #d63384;
      color: white;
    }
    .btn-pink:hover {
      background-color: #c2185b;
    }
    .text-muted a {
      color: #d63384;
      font-weight: 600;
    }
    .text-muted a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2 class="text-center mb-4">Login Admin</h2>

  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" required class="form-control" autocomplete="off">
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" required class="form-control" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-pink w-100">Login</button>
  </form>

  <p class="mt-3 text-center text-muted">
    Belum punya akun? <a href="user_login.php">Login sebagai Pengguna</a>
  </p>
  <p class="text-center text-muted" style="font-size: 0.9em;">
    Hanya admin utama yang bisa menambahkan admin baru.
  </p>
</div>

</body>
</html>