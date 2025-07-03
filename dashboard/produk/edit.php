<?php
session_start();
include '../../inc/koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if ($_FILES['foto']['name']) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], '../../uploads/' . $foto);
    } else {
        $foto = $produk['foto'];
    }

    $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, deskripsi=?, harga=?, stok=?, foto=? WHERE id=?");
    $stmt->execute([$nama, $deskripsi, $harga, $stok, $foto, $id]);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="mb-4 text-primary">Edit Produk</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
    </div>
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" value="<?= $produk['harga'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" value="<?= $produk['stok'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Foto (opsional)</label><br>
      <img src="../../uploads/<?= $produk['foto'] ?>" width="80"><br>
      <input type="file" name="foto" class="form-control mt-2">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
