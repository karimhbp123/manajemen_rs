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
        UPDATE pegawai_tetap SET
     nama=?,
        nik=?,
        jenis_kelamin=?,
        tempat_lahir=?,
        tanggal_lahir=?,
        agama=?,
        alamat=?,
        nomor_hp=?,
        email=?,
        nip=?,
        pendidikan=?,
        program_studi=?,
        ijazah_terakhir=?,
        jabatan=?,
        unit=?,
        status_kepegawaian=?,
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
        val('tempat_lahir'),
        val('tanggal_lahir'),
        val('agama'),
        val('alamat'),
        val('nomor_hp'),
        val('email'),
        val('nip'),
        val('pendidikan'),
        val('program_studi'),
        val('ijazah_terakhir'),
        val('jabatan'),
        val('unit'),
        val('status_kepegawaian'),
        val('tmt_masuk'),
        val('masa_berlaku'),
        val('status_pegawai'),
        val('tmt_status'),
        $id
    ];

    $types = str_repeat("s", count($params) - 1) . "i";

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: ../../pages/Tetap/index.php?msg=update_success");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }

    $stmt->close();
}
