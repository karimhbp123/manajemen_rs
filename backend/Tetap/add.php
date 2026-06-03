<?php
session_start();
require_once('../../config/db.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function val($key)
    {
        return isset($_POST[$key]) && $_POST[$key] !== ''
            ? htmlspecialchars(trim($_POST[$key]))
            : null;
    }

    // ambil semua field sesuai form
    $nama                = val('nama');
    $nik                 = val('nik');
    $jenis_kelamin       = val('jenis_kelamin');
    $tempat_lahir        = val('tempat_lahir'); // ✅ baru
    $tanggal_lahir       = val('tanggal_lahir');
    $agama               = val('agama'); // ✅ baru
    $alamat              = val('alamat');
    $nomor_hp            = val('nomor_hp');
    $email               = val('email');

    $nip                = val('nip');
    $pendidikan          = val('pendidikan'); // ✅ baru
    $program_studi       = val('program_studi'); // ✅ baru
    $ijazah_terakhir     = val('ijazah_terakhir'); // ✅ baru
    $jabatan             = val('jabatan');
    $unit                = val('unit');
    $status_kepegawaian  = val('status_kepegawaian');
    $tmt_masuk           = val('tmt_masuk');
    $masa_berlaku       = val('masa_berlaku');
    $status_pegawai     = val('status_pegawai');
    $tmt_status         = val('tmt_status');

    // validasi sederhana
    if (!$nama || !$jenis_kelamin) {
        header("Location: ../../pages/Tetap/addTetap.php?msg=error");
        exit;
    }

    // query insert (SUDAH LENGKAP)
    $query = "INSERT INTO pegawai_tetap (
        nama, nik, jenis_kelamin, tempat_lahir, tanggal_lahir, agama,
        alamat, nomor_hp, email,
        nip, pendidikan, program_studi, ijazah_terakhir,
        jabatan, unit, status_kepegawaian, tmt_masuk, masa_berlaku, status_pegawai, tmt_status
    ) VALUES (
    ?, ?, ?, ?, ?, 
    ?, ?, ?, 
    ?, ?, ?, ?, 
    ?, ?, ?, 
    ?, ?, ?, ?, ?
    )";

    $stmt = $koneksi->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $koneksi->error);
    }

    $stmt->bind_param(
        "ssssssssssssssssssss",
        $nama,
        $nik,
        $jenis_kelamin,
        $tempat_lahir,
        $tanggal_lahir,
        $agama,
        $alamat,
        $nomor_hp,
        $email,
        $nip,
        $pendidikan,
        $program_studi,
        $ijazah_terakhir,
        $jabatan,
        $unit,
        $status_kepegawaian,
        $tmt_masuk,
        $masa_berlaku,
        $status_pegawai,
        $tmt_status
    );

    if ($stmt->execute()) {
        header("Location: ../../pages/Tetap/index.php?msg=add_success");
    } else {
        echo "Gagal tambah data: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
} else {
    header("Location: ../../pages/Tetap/index.php");
    exit;
}
