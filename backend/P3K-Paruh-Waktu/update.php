<?php
session_start();
require_once('../../config/db.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);

    function val($key)
    {
        return isset($_POST[$key]) && $_POST[$key] !== ''
            ? htmlspecialchars(trim($_POST[$key]))
            : null;
    }

    $nama              = val('nama');
    $nik               = val('nik');
    $jenis_kelamin     = val('jenis_kelamin');
    $tempat_lahir      = val('tempat_lahir');
    $agama             = val('agama');
    $status_perkawinan = val('status_perkawinan');
    $tanggal_lahir     = val('tanggal_lahir');
    $alamat            = val('alamat');
    $nomor_hp          = val('nomor_hp');
    $email             = val('email');

    $pendidikan        = val('pendidikan');
    $program_studi     = val('program_studi');

    $nip               = val('nip');
    $status_kepegawaian = val('status_kepegawaian');
    $tmt_kepegawaian   = val('tmt_kepegawaian');
    $jabatan           = val('jabatan');
    $unit              = val('unit');
    $tmt_masuk         = val('tmt_masuk');
    $masa_berlaku      = val('masa_berlaku');
    $status_pegawai    = val('status_pegawai');
    $tmt_status        = val('tmt_status');

    $stmt = $koneksi->prepare("
        UPDATE pegawai_p3k_paruh_waktu SET
            nama=?,
            nik=?,
            jenis_kelamin=?,
            tempat_lahir=?,
            agama=?,
            status_perkawinan=?,
            tanggal_lahir=?,
            alamat=?,
            nomor_hp=?,
            email=?,
            pendidikan=?,
            program_studi=?,
            nip=?,
            status_kepegawaian=?,
            tmt_kepegawaian=?,
            jabatan=?,
            unit=?,
            tmt_masuk=?,
            masa_berlaku=?,
            status_pegawai=?,
            tmt_status=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssssssssssssssssssssi",
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
        $tmt_status,
        $id
    );

    if ($stmt->execute()) {
        header("Location: ../../pages/P3K-Paruh-Waktu/index.php?msg=update_success");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }

    $stmt->close();
}