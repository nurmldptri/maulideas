<?php
session_start();

// ✅ Pastikan hanya admin yang dapat mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

include '../../inc/koneksi.php';

// ✅ Pastikan ID tersedia dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?msg=id_tidak_valid");
    exit();
}

$id = intval($_GET['id']);

// ✅ Cek apakah data pesanan dengan ID ini ada
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: index.php?msg=pesanan_tidak_ditemukan");
    exit();
}

// ✅ Hapus file bukti jika ada dan file tersebut benar-benar ada
if (!empty($order['bukti'])) {
    $filePath = "../../uploads/" . $order['bukti'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// ✅ Lanjutkan hapus dari database
$delete = $conn->prepare("DELETE FROM orders WHERE id = ?");
$delete->execute([$id]);

// ✅ Kembali ke index dengan pesan sukses
header("Location: index.php?msg=hapus_sukses");
exit();