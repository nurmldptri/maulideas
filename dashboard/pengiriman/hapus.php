<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../admin_login.php");
  exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM pengiriman WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit();
?>
