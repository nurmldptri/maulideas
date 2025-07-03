<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';

// Ambil pesanan yang belum dikirim (belum ada di tabel pengiriman)
$orders = $conn->query("
  SELECT o.id AS order_id, u.name AS nama_pemesan
  FROM orders o
  JOIN users u ON o.user_id = u.id
  WHERE o.id NOT IN (SELECT order_id FROM pengiriman)
  ORDER BY o.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Proses simpan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $order_id = $_POST['order_id'];
  $kurir = $_POST['kurir'];
  $resi = $_POST['resi'];
  $status = 'Belum Dikirim'; // default status
  $tanggal_kirim = date('Y-m-d H:i:s'); // otomatis isi waktu kirim

  if (!$order_id || !$kurir || !$resi) {
    $error = "Semua field wajib diisi.";
  } else {
    $stmt = $conn->prepare("INSERT INTO pengiriman (order_id, kurir, resi, status, tanggal_kirim) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$order_id, $kurir, $resi, $status, $tanggal_kirim]);

    header("Location: index.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pengiriman - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff0f6, #ffe3f1);
      padding-bottom: 60px;
    }

    .form-container {
      max-width: 700px;
      margin: 60px auto;
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(214, 51, 132, 0.1);
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

    .form-control:focus {
      border-color: #d63384;
      box-shadow: 0 0 0 0.2rem rgba(214, 51, 132, 0.2);
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-pink:hover {
      background-color: #c2185b;
    }

    .btn-outline {
      background-color: #ccc;
      border: none;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
    }

    @media (max-width: 576px) {
      .form-container {
        padding: 25px;
        margin: 30px 16px;
      }
    }
  </style>
</head>
<body>

<div class="form-container">
  <h3>🚚 Tambah Data Pengiriman</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Order (Nama Pemesan)</label>
      <select name="order_id" class="form-control" required>
        <option value="">-- Pilih Pemesan --</option>
        <?php foreach ($orders as $order): ?>
          <option value="<?= $order['order_id'] ?>">#<?= $order['order_id'] ?> - <?= htmlspecialchars($order['nama_pemesan']) ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Kurir</label>
      <input type="text" name="kurir" class="form-control" placeholder="JNE / J&T / SiCepat" required>
    </div>
    <div class="mb-3">
      <label>No. Resi</label>
      <input type="text" name="resi" class="form-control" required>
    </div>
    <div class="text-center mt-4">
      <button type="submit" class="btn btn-pink me-2">💾 Simpan</button>
      <a href="index.php" class="btn btn-outline">← Batal</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>