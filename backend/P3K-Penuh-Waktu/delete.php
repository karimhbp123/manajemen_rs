<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// base
$base_url  = '/manajemen_rs/';
$base_path = $_SERVER['DOCUMENT_ROOT'] . $base_url;

require_once($base_path . 'config/db.php');

// validasi id
if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan";
    exit;
}

$id = intval($_GET['id']);

// query hapus
$query = "DELETE FROM pegawai_p3k_penuh_waktu WHERE id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../../pages/P3K-Penuh-Waktu/index.php?msg=delete_success");
} else {
    echo "Gagal menghapus data";
}

$stmt->close();