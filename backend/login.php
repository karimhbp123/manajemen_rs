<?php
session_start();
include __DIR__ . '/../config/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = $koneksi->query("SELECT * FROM users WHERE username='$username'");
$data = $query->fetch_assoc();

if ($data) {

    if ($password == $data['password']) {

        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['username'];

        header("Location: ../dashboard.php");
        exit;

    } else {
        echo "<script>alert('Password salah'); window.location='../index.php';</script>";
    }

} else {
    echo "<script>alert('Username tidak ditemukan'); window.location='../index.php';</script>";
}
?>
