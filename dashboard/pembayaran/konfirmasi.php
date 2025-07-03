<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  die("ID tidak valid");
}

$query = $conn->prepare("
  SELECT p.*, o.status AS status_order
  FROM pembayaran p
  JOIN orders o ON p.order_id = o.id
  WHERE p.id = ?
");
$query->execute([$id]);
$data = $query->fetch(PDO::FETCH_ASSOC);

if (!$data) {
  die("Data tidak ditemukan");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $status = $_POST['status'];

  // Update status pembayaran
  $update = $conn->prepare("UPDATE pembayaran SET status = ? WHERE id = ?");
  $update->execute([$status, $id]);

  // Update status pesanan juga
  $statusPesanan = $status === 'Dikonfirmasi' ? 'Diproses' : 'Menunggu Pembayaran Ulang';
  $updateOrder = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $updateOrder->execute([$statusPesanan, $data['order_id']]);

  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3 class="text-center mb-4 text-danger">Konfirmasi Pembayaran</h3>
  <div class="card p-4 shadow">
    <form method="POST">
      <p><strong>Order:</strong> #<?= $data['order_id'] ?></p>
      <p><strong>Metode:</strong> <?= $data['metode'] ?></p>
      <p><strong>Status Sekarang:</strong> <?= $data['status'] ?></p>
      <p><strong>Bukti:</strong><br>
        <?php if ($data['bukti_pembayaran']): ?>
          <img src="../../uploads/bukti/<?= $data['bukti_pembayaran'] ?>" alt="Bukti" width="300">
        <?php else: ?>
          <em>Tidak ada</em>
        <?php endif ?>
      </p>
      <div class="mb-3">
        <label>Status Pembayaran</label>
        <select name="status" class="form-control" required>
          <option value="Dikonfirmasi">Dikonfirmasi</option>
          <option value="Ditolak">Ditolak</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
</body>
</html>