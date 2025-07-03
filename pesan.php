<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'inc/koneksi.php';

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = $_POST['nama'];
    $wa       = $_POST['wa'];
    $alamat   = $_POST['alamat'];
    $jumlah   = (int)$_POST['jumlah'];
    $produkArr = isset($_POST['produk']) ? $_POST['produk'] : [];
    $produk   = implode(", ", $produkArr);
    $model    = $_POST['model'];
    $harga    = (int)$_POST['harga'];
    $metode   = $_POST['metode'];
    $userId   = $_SESSION['user']['id'];
    $buktiFileName = null;

    if ($metode === "Transfer" && isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileName = $_FILES['bukti']['name'];
        $fileTmp  = $_FILES['bukti']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowedExtensions)) {
            $buktiFileName = uniqid('bukti_') . '.' . $fileExt;
            move_uploaded_file($fileTmp, 'uploads/' . $buktiFileName);
        } else {
            $errorMessage = "Format file bukti tidak didukung.";
        }
    }

    if (empty($errorMessage)) {
        $rincian = "$produk ($jumlah pcs) - Model: $model";
        $stmt = $conn->prepare("INSERT INTO orders (user_id, nama, wa, alamat, metode, bukti, rincian, status, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, 'Diproses', NOW())");
        $stmt->execute([$userId, $nama, $wa, $alamat, $metode, $buktiFileName, $rincian]);
        $successMessage = "✅ Order berhasil diterima. Terima kasih! 💕";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Pemesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #fff0f6; font-family: 'Segoe UI', sans-serif; }
    .form-container {
      max-width: 650px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(214, 51, 132, 0.15);
    }
    h1 { text-align: center; color: #d63384; font-weight: bold; }
    label { font-weight: 500; color: #d63384; }
    .btn-pink {
      background-color: #d63384;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px;
    }
    .btn-pink:hover { background-color: #c2185b; }
    .rekening-info, .bukti-transfer {
      display: none;
      background: #fff3f8;
      border: 1px solid #f0bcd1;
      padding: 15px;
      border-radius: 10px;
      margin-top: 10px;
      color: #c2185b;
    }

    /* Tombol navigasi bawah */
    .btn-home-round {
      background-color: white;
      border: 2px solid #d63384;
      color: #d63384;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .btn-home-round:hover {
      background-color: #ffe3f1;
      color: #c2185b;
    }
    @media (max-width: 576px) {
      .btn-home-round {
        width: 45px;
        height: 45px;
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container form-container">
  <h1>Form Pemesanan</h1>

  <?php if ($successMessage): ?>
    <div class="alert alert-success text-center"><?= $successMessage ?></div>
  <?php elseif ($errorMessage): ?>
    <div class="alert alert-danger text-center"><?= $errorMessage ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" id="orderForm">
    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nomor WhatsApp</label>
      <input type="text" name="wa" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Alamat</label>
      <textarea name="alamat" rows="3" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Jumlah Pesanan</label>
      <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" value="1" required onchange="updateHarga();">
      <small class="form-text text-muted mt-1">
        📦 Jumlah <strong>1</strong> setara dengan <strong>5 lembar</strong> photostrip.
      </small>
    </div>
    <div class="mb-3">
      <label>Produk</label><br>
      <?php
      $produkList = ['Photostrip Desain 1', 'Photostrip Desain 2', 'Photostrip Desain 3', 'Photostrip Desain 4', 'Photostrip Desain Combo'];
      foreach ($produkList as $produkItem):
      ?>
        <div class="form-check">
          <input class="form-check-input produk-checkbox" type="checkbox" name="produk[]" value="<?= $produkItem ?>" id="<?= $produkItem ?>">
          <label class="form-check-label" for="<?= $produkItem ?>"><?= $produkItem ?></label>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mb-3" id="modelGroup">
      <label>Model Desain</label>
      <small class="form-text text-muted d-block mb-1">
        Kode: <strong>A, B, C, D, E, F, G, H</strong>
      </small>
      <input type="text" name="model" id="modelInput" class="form-control" placeholder="Contoh: A, B, C, D, E">
      <div id="modelError" class="text-danger mt-1 d-none">⚠️ Masukkan tepat 5 huruf A–H, pisah dengan koma.</div>
    </div>

    <div class="mb-3" id="modelTextareaGroup" style="display:none;">
      <label>Model Desain (Custom)</label>
      <small class="form-text text-muted d-block mb-1">Contoh: <em>1B, 2C, 3A, 4B</em></small>
      <textarea name="model" id="modelTextarea" class="form-control" rows="3" placeholder="Contoh: 1A, 2B, 3C"></textarea>
    </div>

    <div class="mb-3">
      <label>Harga</label>
      <input type="text" id="hargaDisplay" class="form-control" value="Rp15.000" readonly>
      <input type="hidden" name="harga" id="harga" value="15000">
    </div>

    <div class="mb-3">
      <label>Metode Pembayaran</label>
      <select name="metode" id="metode" class="form-select" required onchange="tampilkanRekening()">
        <option value="">-- Pilih Metode --</option>
        <option value="Cash">Cash</option>
        <option value="Transfer">Transfer</option>
      </select>
    </div>

    <div class="rekening-info" id="rekeningInfo">
      <strong>Transfer ke Rekening:</strong><br>
      No: <strong>9876 4327 0009 565</strong><br>
      Atas Nama: <strong>Maulida</strong><br>
      Bank: <strong>BRI</strong>
    </div>

    <div class="bukti-transfer" id="buktiTransfer">
      <label for="bukti" class="form-label mt-3">Upload Bukti Transfer</label>
      <input type="file" name="bukti" id="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
    </div>

    <button type="submit" class="btn btn-pink w-100 mt-3">Kirim Pesanan</button>

    <!-- Tombol Navigasi -->
    <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 mt-4">
      <a href="produk.php" class="btn btn-pink w-100 w-sm-auto">← Produk</a>
      <a href="riwayat.php" class="btn btn-pink w-100 w-sm-auto">Riwayat →</a>
      <a href="index.php" class="btn-home-round" title="Beranda">🏠</a>
    </div>
  </form>
</div>

<script>
function tampilkanRekening() {
  const metode = document.getElementById('metode').value;
  document.getElementById('rekeningInfo').style.display = metode === 'Transfer' ? 'block' : 'none';
  document.getElementById('buktiTransfer').style.display = metode === 'Transfer' ? 'block' : 'none';
  document.getElementById('bukti').required = metode === 'Transfer';
}

function updateHarga() {
  const jumlah = parseInt(document.getElementById("jumlah").value || 1);
  const harga = jumlah * 15000;
  document.getElementById("harga").value = harga;
  document.getElementById("hargaDisplay").value = "Rp" + harga.toLocaleString('id-ID');
  toggleModelInput();
}

function toggleModelInput() {
  const jumlah = parseInt(document.getElementById("jumlah").value || 1);
  const produkCheckboxes = document.querySelectorAll(".produk-checkbox:checked");
  const isCombo = Array.from(produkCheckboxes).some(cb => cb.value.includes("Combo"));
  const isMultipleProduk = produkCheckboxes.length > 1;

  const modelGroup = document.getElementById("modelGroup");
  const modelTextareaGroup = document.getElementById("modelTextareaGroup");
  const input = document.getElementById("modelInput");
  const textarea = document.getElementById("modelTextarea");

  if (jumlah > 1 || isCombo || isMultipleProduk) {
    modelGroup.style.display = "none";
    modelTextareaGroup.style.display = "block";
    input.required = false;
    textarea.required = true;
  } else {
    modelGroup.style.display = "block";
    modelTextareaGroup.style.display = "none";
    input.required = true;
    textarea.required = false;
  }
}

document.getElementById("jumlah").addEventListener("change", toggleModelInput);
document.querySelectorAll(".produk-checkbox").forEach(cb => cb.addEventListener("change", toggleModelInput));

document.getElementById("orderForm").addEventListener("submit", function(e) {
  const jumlah = parseInt(document.getElementById("jumlah").value || 1);
  const produkCheckboxes = document.querySelectorAll(".produk-checkbox:checked");
  const isCombo = Array.from(produkCheckboxes).some(cb => cb.value.includes("Combo"));
  const isMultipleProduk = produkCheckboxes.length > 1;

  if (jumlah === 1 && !isCombo && !isMultipleProduk) {
    const modelInput = document.getElementById("modelInput");
    const modelError = document.getElementById("modelError");
    const value = modelInput.value.trim().toUpperCase();
    const models = value.split(',').map(s => s.trim());
    const allowed = ['A','B','C','D','E','F','G','H'];
    const valid = models.every(m => allowed.includes(m));

    if (models.length !== 5 || !valid) {
      e.preventDefault();
      modelError.classList.remove("d-none");
      modelInput.classList.add("is-invalid");
    } else {
      modelError.classList.add("d-none");
      modelInput.classList.remove("is-invalid");
    }
  }
});

updateHarga(); // initial call
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>