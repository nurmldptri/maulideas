<?php
session_start();
include '../../inc/koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit();
?>
