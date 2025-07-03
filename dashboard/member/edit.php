<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pesan = "";

// Ambil data kartu member
$stmt = $conn->prepare("SELECT * FROM kartu_member WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    die("Data kartu member tidak ditemukan.");
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama_member']);
    $email = trim($_POST['email']);
    $nomor = trim($_POST['nomor_member']);

    $update = $conn->prepare("UPDATE kartu_member SET nama_member = ?, email = ?, nomor_member = ? WHERE id = ?");
    $update->execute([$nama, $email, $nomor, $id]);

    $pesan = "✅ Data kartu member berhasil diperbarui.";

    // Refresh data
    $stmt->execute([$id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Kartu Member - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
    }
    .card-form {
      background-color: #ffffff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 18px rgba(214, 51, 132, 0.1);
    }
    h2 {
      color: #d63384;
      font-weight: bold;
      text-align: center;
      margin-bottom: 25px;
    }
    label {
      color: #d63384;
      font-weight: 600;
    }
    .btn-pink {
      background-color: #d63384;
      color: white;
      border: none;
      border-radius: 10px;
      padding: 10px 24px;
      font-weight: bold;
      width: 100%;
    }
    .btn-pink:hover {
      background-color: #c2185b;
    }
    .alert-success {
      margin-bottom: 20px;
      text-align: center;
    }
    .btn-kembali {
      display: block;
      margin-top: 15px;
      text-align: center;
      color: #d63384;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }
    .btn-kembali:hover {
      text-decoration: underline;
      color: #c2185b;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card-form">
    <h2>📝 Edit Kartu Member</h2>

    <?php if ($pesan): ?>
      <div class="alert alert-success"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="nama_member" class="form-label">Nama Member</label>
        <input type="text" name="nama_member" class="form-control" value="<?= htmlspecialchars($member['nama_member']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Member</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="nomor_member" class="form-label">Nomor Member</label>
        <input type="text" name="nomor_member" class="form-control" value="<?= htmlspecialchars($member['nomor_member']) ?>" required>
      </div>
      <button type="submit" class="btn btn-pink">💾 Simpan Perubahan</button>
    </form>

    <a href="index.php" class="btn-kembali">⬅️ Kembali ke Daftar Member</a>
  </div>
</div>

</body>
</html>
