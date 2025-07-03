<?php
session_start();
include 'inc/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: blog.php");
    exit();
}

$stmt = $conn->prepare("SELECT b.*, u.username as author FROM blogs b JOIN users u ON b.author_id = u.id WHERE b.id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    echo "<h3 class='text-center mt-5'>Artikel tidak ditemukan 😢</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($blog['title']) ?> - Photostrip Maulideas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Segoe UI', sans-serif;
    }
    .blog-detail {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(214, 51, 132, 0.15);
      margin-top: 40px;
    }
    h1 {
      color: #d63384;
    }
    .meta {
      color: #999;
      font-size: 14px;
      margin-bottom: 20px;
    }
    .btn-back {
      background-color: #ffe3f1;
      color: #d63384;
      font-weight: bold;
      border: 1px solid #f5b4d1;
      margin-top: 30px;
    }
    .btn-back:hover {
      background-color: #f8c7dc;
      color: white;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="blog-detail">
    <h1><?= htmlspecialchars($blog['title']) ?></h1>
    <p class="meta">Ditulis oleh <strong><?= htmlspecialchars($blog['author']) ?></strong> pada <?= date('d M Y H:i', strtotime($blog['created_at'])) ?></p>
    <div><?= nl2br($blog['content']) ?></div>

    <a href="blog.php" class="btn btn-back mt-4">← Kembali ke Blog</a>
  </div>
</div>

</body>
</html>
