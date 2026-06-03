<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "manajemen_rs";
$port = 3306;

// koneksi
$koneksi = new mysqli($host, $user, $pass, $db, $port);

// cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
