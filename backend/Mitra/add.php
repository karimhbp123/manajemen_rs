<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

$base_url  = '/manajemen_rs/';
$base_path = $_SERVER['DOCUMENT_ROOT'] . $base_url;

require_once($base_path . 'config/db.php');

// ambil data dari form
$nama            = $_POST['nama'];
$tmt             = $_POST['tmt'];
$jabatan         = $_POST['jabatan'];
$jenis_kelamin   = $_POST['jenis_kelamin'];
$unit            = $_POST['unit'];
$no_hp           = $_POST['no_hp'];
$email           = $_POST['email'];
$alamat          = $_POST['alamat'];
$status_pegawai  = $_POST['status_pegawai'];
$masa_berlaku    = $_POST['masa_berlaku'];
$tmt_status      = $_POST['tmt_status'] ?? null;

// LOGIC: kalau AKTIF → tmt_status NULL
if ($status_pegawai == 'AKTIF') {
    $tmt_status = "NULL";
} else {
    if (!empty($_POST['tmt_status'])) {
        $tmt_status = "'" . $_POST['tmt_status'] . "'";
    } else {
        $tmt_status = "NULL";
    }
}

// query insert
$query = "INSERT INTO pegawai_mitra 
(nama, tmt, jabatan, jenis_kelamin, unit, no_hp, email, alamat, status_pegawai, masa_berlaku, tmt_status)
VALUES 
('$nama', '$tmt', '$jabatan', '$jenis_kelamin', '$unit', '$no_hp', '$email', '$alamat', '$status_pegawai', '$masa_berlaku', $tmt_status)";

if ($koneksi->query($query)) {
    header("Location: ../../pages/Mitra/index.php?msg=add_success");
} else {
    echo "Gagal menyimpan data: " . $koneksi->error;
}
