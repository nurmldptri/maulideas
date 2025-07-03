<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM pengiriman WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
  echo "Data tidak ditemukan.";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $kurir = $_POST['kurir'];
  $resi = $_POST['resi'];
  $status = $_POST['status'];
  $tanggal_kirim = $_POST['tanggal_kirim'];

  $update = $conn->prepare("UPDATE pengiriman SET kurir=?, resi=?, status=?, tanggal_kirim=? WHERE id=?");
  $update->execute([$kurir, $resi, $status, $tanggal_kirim, $id]);

  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengiriman</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffe3f1, #fff0f6);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 80px;
    }

    .form-container {
      max-width: 600px;
      margin: 60px auto;
      background: white;
      padding: 30px 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(214, 51, 132, 0.15);
    }

    h3 {
      color: #d63384;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }

    label {
      color: #d63384;
      font-weight: 500;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #f5b4d1;
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: bold;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-pink:hover {
      background-color: #c2185b;
    }

    .btn-secondary {
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h3>Edit Data Pengiriman</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Kurir</label>
      <input type="text" name="kurir" class="form-control" value="<?= htmlspecialchars($data['kurir']) ?>" required>
    </div>
    <div class="mb-3">
      <label>No. Resi</label>
      <input type="text" name="resi" class="form-control" value="<?= htmlspecialchars($data['resi']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="Belum Dikirim" <?= $data['status'] == 'Belum Dikirim' ? 'selected' : '' ?>>Belum Dikirim</option>
        <option value="Dikirim" <?= $data['status'] == 'Dikirim' ? 'selected' : '' ?>>Dikirim</option>
        <option value="Diterima" <?= $data['status'] == 'Diterima' ? 'selected' : '' ?>>Diterima</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Tanggal Kirim</label>
      <input type="datetime-local" name="tanggal_kirim" class="form-control"
             value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_kirim'])) ?>">
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <button type="submit" class="btn btn-pink">💾 Simpan Perubahan</button>
      <a href="index.php" class="btn btn-secondary">← Batal</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
