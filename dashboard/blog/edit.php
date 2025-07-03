<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../../inc/koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    echo "Data tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE blogs SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $content, $id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Artikel Blog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #fff0f6, #ffe3f1);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 80px;
    }

    .form-container {
      max-width: 700px;
      margin: 60px auto;
      background: white;
      padding: 30px 40px;
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

    .form-control {
      border-radius: 10px;
      border: 1px solid #f5b4d1;
    }

    .btn-pink {
      background-color: #d63384;
      color: white;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: bold;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-pink:hover {
      background-color: #c2185b;
    }

    .btn-secondary {
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>✏️ Edit Artikel Blog</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Judul</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Konten</label>
      <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($blog['content']) ?></textarea>
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <button type="submit" class="btn btn-pink">💾 Simpan Perubahan</button>
      <a href="index.php" class="btn btn-secondary">← Kembali</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>