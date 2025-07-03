<?php
include 'inc/koneksi.php';

// Data admin baru
$email = 'maulida@gmail.com';
$password = hash('sha256', '1234'); // harus sama seperti di login.php
$username = 'Maulida';
$role = 'admin';

// Cek apakah sudah ada admin dengan email ini
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$cek = $stmt->fetch();

if ($cek) {
    echo "Admin dengan email ini sudah ada!";
} else {
    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (email, password, username, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $password, $username, $role]);

    echo "Admin berhasil ditambahkan!";
}
?>
