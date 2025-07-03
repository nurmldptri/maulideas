<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO blogs (title, content, author_id) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $author_id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Artikel Blog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff0f6, #ffe3f1);
      padding-bottom: 80px;
    }

    .blog-form-container {
      max-width: 700px;
      margin: 60px auto;
      background-color: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(214, 51, 132, 0.15);
    }

    h2 {
      color: #d63384;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }

    label {
      color: #d63384;
      font-weight: 500;
    }

    .form-control:focus {
      border-color: #d63384;
      box-shadow: 0 0 0 0.2rem rgba(214, 51, 132, 0.2);
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      border: none;
      padding: 10px 24px;
      transition: all 0.3s ease;
    }

    .btn-pink:hover {
      background-color: #c2185b;
    }

    .btn-secondary {
      background-color: #ccc;
      border: none;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 24px;
    }

    .btn-secondary:hover {
      background-color: #999;
    }
  </style>
</head>
<body>

<div class="container blog-form-container">
  <h2>📝 Tambah Artikel Blog</h2>
  <form method="POST">
    <div class="mb-3">
      <label for="title" class="form-label">Judul Artikel</label>
      <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="content" class="form-label">Konten</label>
      <textarea name="content" id="content" class="form-control" rows="8" placeholder="Tulis isi artikel di sini..." required></textarea>
    </div>
    <div class="text-center mt-4">
      <button type="submit" class="btn btn-pink">✅ Simpan Artikel</button>
      <a href="index.php" class="btn btn-secondary ms-2">← Kembali</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>