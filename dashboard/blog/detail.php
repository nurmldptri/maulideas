<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'] ?? null;
$stmt = $conn->prepare("SELECT b.*, u.username FROM blogs b JOIN users u ON b.author_id = u.id WHERE b.id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    echo "Artikel tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Blog - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #fff0f6, #ffe3f1);
      font-family: 'Segoe UI', sans-serif;
      color: #333;
      padding-bottom: 80px;
    }

    .blog-container {
      max-width: 800px;
      margin: 60px auto;
      background-color: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 4px 20px rgba(214, 51, 132, 0.15);
    }

    h2.blog-title {
      color: #d63384;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .blog-meta {
      color: #999;
      font-size: 0.95rem;
      margin-bottom: 25px;
    }

    .blog-content {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #444;
      white-space: pre-wrap;
    }

    .btn-back {
      background-color: #ffe3f1;
      color: #d63384;
      border: 1px solid #f5b4d1;
      font-weight: bold;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .btn-back:hover {
      background-color: #f8c7dc;
      color: white;
    }
  </style>
</head>
<body>

<div class="container blog-container">
  <h2 class="blog-title"><?= htmlspecialchars($blog['title']) ?></h2>
  <p class="blog-meta">Ditulis oleh <strong><?= htmlspecialchars($blog['username']) ?></strong> pada <?= date('d-m-Y H:i', strtotime($blog['created_at'])) ?></p>
  <hr>
  <div class="blog-content"><?= nl2br(htmlspecialchars($blog['content'])) ?></div>
  <a href="index.php" class="btn btn-back mt-4">← Kembali ke Daftar Artikel</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
