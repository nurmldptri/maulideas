<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: user_login.php");
    exit();
}
include 'inc/koneksi.php';

$user_id = $_SESSION['user']['id'];

// Ambil semua pesanan user yang belum dibayar
$sql = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND status = 'Menunggu Pembayaran'");
$sql->execute([$user_id]);
$orders = $sql->fetchAll(PDO::FETCH_ASSOC);

// Saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $metode = $_POST['metode'];
    $tanggal_bayar = date('Y-m-d H:i:s');
    $status = 'Menunggu Konfirmasi';

    // Validasi bahwa order benar milik user
    $cekOrder = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $cekOrder->execute([$order_id, $user_id]);
    if ($cekOrder->rowCount() === 0) {
        die("Order tidak ditemukan atau bukan milik Anda.");
    }

    // Upload bukti pembayaran
    $bukti = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];
    $upload_dir = "uploads/bukti/";
    $filename = uniqid() . '_' . $bukti;
    move_uploaded_file($tmp, $upload_dir . $filename);

    // Simpan ke database
    $insert = $conn->prepare("INSERT INTO pembayaran (order_id, metode, status, tanggal_bayar, bukti_pembayaran) VALUES (?, ?, ?, ?, ?)");
    $insert->execute([$order_id, $metode, $status, $tanggal_bayar, $filename]);

    // Update status pesanan
    $update = $conn->prepare("UPDATE orders SET status = 'Menunggu Konfirmasi Pembayaran' WHERE id = ?");
    $update->execute([$order_id]);

    header("Location: pembayaran.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Upload Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fff0f6; font-family: 'Segoe UI', sans-serif; }
    .card { background-color: white; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .btn-pink { background-color: #d63384; color: white; }
    .btn-pink:hover { background-color: #c2185b; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card">
    <h3 class="text-center mb-4 text-danger">Upload Bukti Pembayaran</h3>
    <?php if (count($orders) > 0): ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Pesanan</label>
        <select name="order_id" class="form-control" required>
          <option value="">-- Pilih Pesanan --</option>
          <?php foreach ($orders as $o): ?>
            <option value="<?= $o['id'] ?>">Order #<?= $o['id'] ?> - <?= htmlspecialchars($o['nama']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="metode" class="form-control" required>
          <option value="Transfer BCA">Transfer BCA</option>
          <option value="Transfer BRI">Transfer BRI</option>
          <option value="QRIS">QRIS</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Upload Bukti Pembayaran</label>
        <input type="file" name="bukti" class="form-control" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-pink">Kirim</button>
      <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
    <?php else: ?>
      <p class="text-center text-muted">Tidak ada pesanan menunggu pembayaran.</p>
      <div class="text-center">
        <a href="index.php" class="btn btn-secondary">Kembali</a>
      </div>
    <?php endif ?>
  </div>
</div>
</body>
</html>