<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'inc/koneksi.php';

$id = $_GET['id'] ?? null;
$userId = $_SESSION['user']['id'];

if (!$id || !is_numeric($id)) {
    echo "ID tidak valid.";
    exit;
}

if ($_SESSION['user']['role'] === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
}
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo "❌ Data tidak ditemukan atau Anda tidak berhak melihat struk ini.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pemesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
    }
    .struk-container {
      max-width: 600px;
      margin: 50px auto;
      background-color: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 0 12px rgba(214, 51, 132, 0.2);
    }
    h2 {
      color: #d63384;
      text-align: center;
      margin-bottom: 20px;
    }
    .table th {
      width: 40%;
      color: #d63384;
    }
    .btn-print {
      background-color: #d63384;
      color: white;
      border: none;
      margin-top: 20px;
    }
    .btn-print:hover {
      background-color: #c2185b;
    }
    @media print {
      .btn-print, .btn-secondary {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="struk-container">
  <h2>🧾 Struk Pemesanan</h2>
  <table class="table table-bordered">
    <tr><th>Nama</th><td><?= htmlspecialchars($order['nama']) ?></td></tr>
    <tr><th>No WA</th><td><?= htmlspecialchars($order['wa']) ?></td></tr>
    <tr><th>Alamat</th><td><?= nl2br(htmlspecialchars($order['alamat'])) ?></td></tr>
    <tr><th>Metode</th><td><?= $order['metode'] ?></td></tr>
    <tr><th>Rincian</th><td><?= nl2br(htmlspecialchars($order['rincian'])) ?></td></tr>
    <tr><th>Tanggal</th><td><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td></tr>
    <tr><th>Status</th><td>
      <span class="badge bg-<?= 
        $order['status'] == 'Selesai' ? 'success' : 
        ($order['status'] == 'Diproses' ? 'warning' : 'secondary') ?>">
        <?= $order['status'] ?>
      </span></td></tr>
    <?php if ($order['metode'] === 'Transfer' && $order['bukti']) : ?>
      <tr><th>Bukti Transfer</th>
        <td><a href="uploads/<?= htmlspecialchars($order['bukti']) ?>" target="_blank">Lihat Bukti</a></td>
      </tr>
    <?php endif; ?>
  </table>

  <div class="text-center">
    <?php if ($order['status'] === 'Selesai') : ?>
      <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Struk</button>
    <?php else: ?>
      <p class="text-muted">Struk final hanya bisa dicetak jika status pesanan <strong>Selesai</strong>.</p>
    <?php endif; ?>
    <a href="riwayat.php" class="btn btn-secondary mt-2">← Kembali</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
