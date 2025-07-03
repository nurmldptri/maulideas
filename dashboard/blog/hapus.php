<?php
session_start();

// Cek apakah user sudah login dan berperan sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

include '../../inc/koneksi.php';

// Ambil ID dari parameter URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Hapus data blog berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
}

// Redirect kembali ke halaman index blog
header("Location: index.php");
exit();
?>