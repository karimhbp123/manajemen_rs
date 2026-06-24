<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

require_once 'config/db.php';

$q = trim($_GET['search'] ?? $_GET['q'] ?? '');

if ($q == '') {
    header("Location: dashboard.php");
    exit;
}

$q = mysqli_real_escape_string($koneksi, $q);

/*
|--------------------------------------------------------------------------
| KONTRAK
|--------------------------------------------------------------------------
*/
$cek = mysqli_query(
    $koneksi,
    "SELECT id
     FROM pegawai_kontrak
     WHERE nama LIKE '%$q%'
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: pages/Kontrak/index.php?search=" . urlencode($q));
    exit;
}

/*
|--------------------------------------------------------------------------
| PNS
|--------------------------------------------------------------------------
*/
$cek = mysqli_query(
    $koneksi,
    "SELECT id
     FROM pegawai_pns
     WHERE nama LIKE '%$q%'
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: pages/PNS/index.php?search=" . urlencode($q));
    exit;
}

/*
|--------------------------------------------------------------------------
| PPPK PENUH WAKTU
|--------------------------------------------------------------------------
*/
$cek = mysqli_query(
    $koneksi,
    "SELECT id
     FROM pegawai_p3k_penuh_waktu
     WHERE nama LIKE '%$q%'
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: pages/P3K-Penuh-Waktu/index.php?search=" . urlencode($q));
    exit;
}

/*
|--------------------------------------------------------------------------
| PPPK PARUH WAKTU
|--------------------------------------------------------------------------
*/
$cek = mysqli_query(
    $koneksi,
    "SELECT id
     FROM pegawai_p3k_paruh_waktu
     WHERE nama LIKE '%$q%'
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: pages/P3K-Paruh-Waktu/index.php?search=" . urlencode($q));
    exit;
}

/*
|--------------------------------------------------------------------------
| TETAP
|--------------------------------------------------------------------------
*/
$cek = mysqli_query(
    $koneksi,
    "SELECT id
     FROM pegawai_tetap
     WHERE nama LIKE '%$q%'
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: pages/Tetap/index.php?search=" . urlencode($q));
    exit;
}

/*
|--------------------------------------------------------------------------
| MITRA
|--------------------------------------------------------------------------
*/
$cek = mysqli_query(
    $koneksi,
    "SELECT id
     FROM pegawai_mitra
     WHERE nama LIKE '%$q%'
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    header("Location: pages/Mitra/index.php?search=" . urlencode($q));
    exit;
}

echo "Data pegawai tidak ditemukan";