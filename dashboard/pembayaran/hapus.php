<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID tidak ditemukan.");
}

// Ambil nama file bukti pembayaran sebelum dihapus
$stmt = $conn->prepare("SELECT bukti_pembayaran FROM pembayaran WHERE id = ?");
$stmt->execute([$id]);
$pembayaran = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pembayaran) {
    die("Data tidak ditemukan.");
}

// Hapus file bukti jika ada
if (!empty($pembayaran['bukti_pembayaran'])) {
    $filepath = "../../uploads/bukti/" . $pembayaran['bukti_pembayaran'];
    if (file_exists($filepath)) {
        unlink($filepath);
    }
}

// Hapus data dari tabel pembayaran
$delete = $conn->prepare("DELETE FROM pembayaran WHERE id = ?");
$delete->execute([$id]);

header("Location: index.php");
exit();
?>
