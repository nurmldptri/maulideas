<?php
if (!isset($_SESSION)) session_start();
$currentPath = $_SERVER['REQUEST_URI'];
?>

<!-- HIASAN ATAS NAVBAR -->
<div style="height: 8px; background: linear-gradient(90deg, #ffb6c1, #ffcce0, #ffe3f1, #ffcce0, #ffb6c1); box-shadow: 0 2px 5px rgba(214, 51, 132, 0.1);"></div>

<!-- NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #ffe3f1; padding: 10px 20px;">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php" style="display: flex; align-items: center; gap: 10px; font-weight: bold; color: #d63384;">
      <img src="../../assets/img/logo_maulideas.jpg" alt="Logo" style="width: 45px; height: 45px; object-fit: cover; border-radius: 10px;">
      Photostrip Maulideas
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navmenu">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/dashboard/index.php') !== false ? 'active' : '' ?>" href="../index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/laporan/') !== false ? 'active' : '' ?>" href="../laporan/admin.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/member/') !== false ? 'active' : '' ?>" href="../member/index.php">Member</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/pengguna/') !== false ? 'active' : '' ?>" href="../pengguna/index.php">Pengguna</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/produk/') !== false ? 'active' : '' ?>" href="../produk/index.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/orders/') !== false ? 'active' : '' ?>" href="../orders/index.php">Pesanan</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/pengiriman/') !== false ? 'active' : '' ?>" href="../pengiriman/index.php">Pengiriman</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/pembayaran/') !== false ? 'active' : '' ?>" href="../pembayaran/index.php">Pembayaran</a></li>
        <li class="nav-item"><a class="nav-link <?= strpos($currentPath, '/blog/') !== false ? 'active' : '' ?>" href="../blog/index.php">Blog</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            👤 <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item text-danger" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
  .nav-link {
    background-color: #f8c7dc;
    color: #d63384 !important;
    font-weight: bold;
    margin: 4px;
    padding: 8px 14px;
    border-radius: 10px;
    border: 2px solid #f5b4d1;
    transition: all 0.3s ease;
    text-align: center;
  }

  .nav-link.active,
  .nav-link:hover {
    background-color: #d63384 !important;
    color: white !important;
  }

  .dropdown-toggle::after {
    margin-left: 6px;
  }
</style>
