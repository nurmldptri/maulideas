<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
include 'inc/koneksi.php';

$userId = $_SESSION['user']['id'];
$pesan = "";

// Cek apakah data pelanggan sudah ada
$stmt = $conn->prepare("SELECT * FROM pelanggan WHERE user_id = ?");
$stmt->execute([$userId]);
$pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alamat = trim($_POST['alamat']);
    $no_wa = trim($_POST['no_wa']);

    if ($pelanggan) {
        $update = $conn->prepare("UPDATE pelanggan SET alamat = ?, no_wa = ? WHERE user_id = ?");
        $update->execute([$alamat, $no_wa, $userId]);
        $pesan = "✅ Data berhasil diperbarui!";
    } else {
        $insert = $conn->prepare("INSERT INTO pelanggan (user_id, alamat, no_wa) VALUES (?, ?, ?)");
        $insert->execute([$userId, $alamat, $no_wa]);
        $pesan = "✅ Data berhasil disimpan!";
    }

    // Refresh data
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE user_id = ?");
    $stmt->execute([$userId]);
    $pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Pelanggan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #fff0f6; font-family: 'Segoe UI', sans-serif;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg" style="background-color: #ffe3f1; padding: 10px 20px;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-decoration-none" href="index.php" style="color: #d63384;">
      <img src="assets/img/logo_maulideas.jpg" alt="Logo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
      Photostrip Maulideas
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navmenu">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pelanggan_form.php' ? 'active' : '' ?>" href="pelanggan_form.php">Pribadi</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'kartu_member.php' ? 'active' : '' ?>" kartu_member.php">Karmem</a></li> 
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'desain.php' ? 'active' : '' ?>" href="desain.php">Desain</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'produk.php' ? 'active' : '' ?>" href="produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pesan.php' ? 'active' : '' ?>" href="pesan.php">Pesan</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'riwayat.php' ? 'active' : '' ?>" href="riwayat.php">Riwayat</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pengiriman.php' ? 'active' : '' ?>" href="pengiriman.php">Pengiriman</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'pembayaran.php' ? 'active' : '' ?>" href="pembayaran.php">Pembayaran</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'blog.php' ? 'active' : '' ?>" href="blog.php">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Keluar</a></li>
      </ul>
      </ul>
    </div>
  </div>
</nav>

<style>
  .navbar-nav .nav-link {
    background-color: #f8c7dc;
    color: #d63384 !important;
    font-weight: bold;
    margin: 4px;
    padding: 8px 16px;
    border-radius: 10px;
    border: 2px solid #f5b4d1;
    transition: all 0.3s ease;
    text-align: center;
  }

  .navbar-nav .nav-link:hover {
    background-color: #d63384;
    color: white !important;
  }

  .navbar-nav .nav-link.active {
    background-color: #d63384 !important;
    color: white !important;
    border-color: #d63384;
  }

  .form-container {
    max-width: 600px;
    margin: 40px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(214, 51, 132, 0.1);
  }

  h2 {
    text-align: center;
    color: #d63384;
    margin-bottom: 30px;
  }

  .btn-pink {
    background-color: #d63384;
    color: white;
    font-weight: bold;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    width: 100%;
  }

  .btn-pink:hover {
    background-color: #c2185b;
  }

  .btn-round-home {
    background-color: #fff;
    border: 2px solid #d63384;
    color: #d63384;
    font-size: 20px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .btn-round-home:hover {
    background-color: #ffe3f1;
    color: #c2185b;
  }

  .nav-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
    align-items: stretch;
  }

  @media (min-width: 576px) {
    .nav-buttons {
      flex-direction: row;
      justify-content: space-between;
    }
  }
</style>

<!-- FORM -->
<div class="form-container">
  <h2><?= $pelanggan ? 'Edit Data Pelanggan' : 'Isi Data Pelanggan' ?></h2>

  <?php if ($pesan): ?>
    <div class="alert alert-success text-center"><?= $pesan ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="no_wa" class="form-label">Nomor WhatsApp</label>
      <input type="text" class="form-control" name="no_wa" id="no_wa" required value="<?= htmlspecialchars($pelanggan['no_wa'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label for="alamat" class="form-label">Alamat Lengkap</label>
      <textarea class="form-control" name="alamat" id="alamat" rows="4" required><?= htmlspecialchars($pelanggan['alamat'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-pink"><?= $pelanggan ? 'Perbarui Data' : 'Simpan Data' ?></button>
  </form>

  <div class="nav-buttons mt-4">
    <a href="blog.php" class="btn btn-outline-secondary">← Blog</a>
    <a href="kartu_member.php" class="btn btn-outline-secondary">Karmem →</a>
    <a href="index.php" class="btn-round-home" title="Beranda">🏠</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>