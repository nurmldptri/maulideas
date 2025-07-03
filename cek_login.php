<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../user_login.php");
    exit();
}
?>