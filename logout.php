<?php
session_start();
session_unset();
session_destroy();

// Redirect sesuai role terakhir
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header("Location: admin_login.php");
} else {
    header("Location: user_login.php");
}
exit();
?>
