<?php
session_start();
if ($_SESSION['user']['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

include '../../inc/koneksi.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<div style='padding:20px;color:red;'>❌ Data tidak ditemukan.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Pesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(214, 51, 132, 0.1);
      margin-top: 40px;
    }
    h2 {
      color: #d63384;
      font-weight: bold;
      margin-bottom: 30px;
    }
    th {
      width: 180px;
      color: #d63384;
    }
    .btn-back {
      background-color: #d63384;
      color: white;
      border: none;
    }
    .btn-back:hover {
      background-color: #c2185b;
    }
    a.bukti-link {
      text-decoration: none;
      color: #d63384;
      font-weight: bold;
    }
    a.bukti-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <h2 class="text-center">📦 Detail Pesanan</h2>
    <table class="table table-bordered">
      <tr><th>Nama</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
      <tr><th>No WhatsApp</th>
        <td><a href="https://wa.me/<?= htmlspecialchars($data['wa']) ?>" target="_blank"><?= htmlspecialchars($data['wa']) ?></a></td>
      </tr>
      <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat']) ?></td></tr>
      <tr><th>Metode Pembayaran</th><td><?= $data['metode'] ?></td></tr>
      <tr><th>Status</th>
        <td>
          <span class="badge bg-<?= 
            $data['status'] == 'Selesai' ? 'success' : 
            ($data['status'] == 'Diproses' ? 'warning' : 
            ($data['status'] == 'Batal' ? 'danger' : 'secondary')) ?>">
            <?= $data['status'] ?>
          </span>
        </td>
      </tr>
      <tr><th>Rincian Orderan</th><td><?= nl2br(htmlspecialchars($data['rincian'])) ?></td></tr>
      <tr><th>Tanggal Order</th><td><?= date('d-m-Y H:i', strtotime($data['created_at'])) ?></td></tr>
      <?php if ($data['metode'] === 'Transfer' && $data['bukti']) : ?>
        <tr>
          <th>Bukti Transfer</th>
          <td><a href="../../uploads/<?= htmlspecialchars($data['bukti']) ?>" class="bukti-link" target="_blank">📎 Lihat Bukti</a></td>
        </tr>
      <?php endif; ?>
    </table>
    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-back">← Kembali ke Daftar</a>
    </div>
  </div>
</div>

</body>
</html>
