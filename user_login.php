<?php
session_start();
include 'inc/koneksi.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $passwordInput = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($passwordInput, $user['password'])) {
        if ($user['role'] !== 'user') {
            $error = "Akses ditolak. Hanya pengguna biasa yang dapat login di sini.";
        } else {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            header("Location: index.php");
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
  <title>Login Pengguna - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    .text-link {
      color: #d63384;
      font-weight: 600;
      text-decoration: none;
    }
    .text-link:hover {
      text-decoration: underline;
      color: #c2185b;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2 class="text-center mb-4">Login Pengguna</h2>

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

  <p class="mt-3 text-center">Belum punya akun? <a href="signup.php" class="text-link">Daftar di sini</a></p>
</div>

</body>
</html>