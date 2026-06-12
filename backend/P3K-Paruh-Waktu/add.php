<?php
session_start();
require_once('../../config/db.php');

// cek login
if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

// cek method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // fungsi helper biar aman
    function val($key)
    {
        return isset($_POST[$key]) && $_POST[$key] !== ''
            ? htmlspecialchars(trim($_POST[$key]))
            : null;
    }

    // ambil data
    $nama                = val('nama');
    $nik                 = val('nik');
    $jenis_kelamin       = val('jenis_kelamin');
    $tanggal_lahir       = val('tanggal_lahir');
    $alamat              = val('alamat');
    $nomor_hp            = val('nomor_hp');
    $email               = val('email');

    $nip                 = val('nip');
    $status_kepegawaian  = val('status_kepegawaian');
    $tmt_kepegawaian     = val('tmt_kepegawaian');
    $jabatan             = val('jabatan');
    $unit                = val('unit');
    $tmt_masuk           = val('tmt_masuk');
    $masa_berlaku       = val('masa_berlaku');
    $status_pegawai      = val('status_pegawai');
    $tmt_status          = val('tmt_status');

    $tempat_lahir        = val('tempat_lahir');
    $agama               = val('agama');
    $status_perkawinan   = val('status_perkawinan');
    $pendidikan          = val('pendidikan');
    $program_studi       = val('program_studi');

    // validasi sederhana
    if (!$nama || !$jenis_kelamin) {
        header("Location: ../../pages/P3K-Paruh-Waktu/addP3KPR.php?msg=error");
        exit;
    }

    // query insert
    $query = "INSERT INTO pegawai_p3k_paruh_waktu (
    nama, nik, jenis_kelamin, tempat_lahir, agama, status_perkawinan,
    tanggal_lahir, alamat, nomor_hp, email,
    pendidikan, program_studi,
    nip, status_kepegawaian, tmt_kepegawaian,
    jabatan, unit, tmt_masuk, masa_berlaku,
    status_pegawai, tmt_status
) VALUES (
    ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?,
    ?, ?,
    ?, ?, ?,
    ?, ?, ?, ?,
    ?, ?, ?
)";

    $stmt = $koneksi->prepare($query);

    $stmt->bind_param(
        "sssssssssssssssssssss",
        $nama,
        $nik,
        $jenis_kelamin,
        $tempat_lahir,
        $agama,
        $status_perkawinan,
        $tanggal_lahir,
        $alamat,
        $nomor_hp,
        $email,
        $pendidikan,
        $program_studi,
        $nip,
        $status_kepegawaian,
        $tmt_kepegawaian,
        $jabatan,
        $unit,
        $tmt_masuk,
        $masa_berlaku,
        $status_pegawai,
        $tmt_status
    );

    if ($stmt->execute()) {
        header("Location: ../../pages/P3K-Paruh-Waktu/index.php?msg=add_success");
    } else {
        header("Location: ../../pages/P3K-Paruh-Waktu/addP3KPR.php?msg=error");
    }

    $stmt->close();
    $koneksi->close();
} else {
    header("Location: ../../pages/P3K-Paruh-Waktu/index.php");
    exit;
}
