<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $target = '../../uploads/' . $foto;

    if (move_uploaded_file($tmp, $target)) {
        $stmt = $conn->prepare("INSERT INTO produk (nama_produk, deskripsi, harga, stok, foto) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $deskripsi, $harga, $stok, $foto]);
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="mb-4 text-primary">Tambah Produk</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4"></textarea>
    </div>
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" value="0" required>
    </div>
    <div class="mb-3">
      <label>Foto</label>
      <input type="file" name="foto" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
