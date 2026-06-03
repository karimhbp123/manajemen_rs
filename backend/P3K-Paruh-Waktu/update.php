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
        return isset($_POST[$key]) && $_POST[$key] !== '' ? $_POST[$key] : null;
    }

    $stmt = $koneksi->prepare("
        UPDATE pegawai_p3k_paruh_waktu SET
            nama=?,
            nik=?,
            jenis_kelamin=?,
            tanggal_lahir=?,
            alamat=?,
            nomor_hp=?,
            email=?,
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

    $params = [
        val('nama'),
        val('nik'),
        val('jenis_kelamin'),
        val('tanggal_lahir'),
        val('alamat'),
        val('nomor_hp'),
        val('email'),
        val('nip'),
        val('status_kepegawaian'),
        val('tmt_kepegawaian'),
        val('jabatan'),
        val('unit'),
        val('tmt_masuk'),
        val('masa_berlaku'),
        val('status_pegawai'),
        val('tmt_status'),
        $id
    ];

    $types = str_repeat("s", count($params) - 1) . "i";

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: ../../pages/P3K-Paruh-Waktu/index.php?msg=update_success");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }

    $stmt->close();
}
