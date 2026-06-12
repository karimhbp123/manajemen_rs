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

    function intval_or_null($key)
    {
        return isset($_POST[$key]) && $_POST[$key] !== '' ? intval($_POST[$key]) : null;
    }

    $stmt = $koneksi->prepare("
        UPDATE pegawai_p3k_penuh_waktu SET
            nama=?, nip=?, nik=?, npwp=?, jenis_kelamin=?, agama=?,
            tempat_lahir=?, tgl_lahir=?, status_perkawinan=?, nama_suami_istri=?, nama_anak=?,
            no_akte=?, tgl_akta_nikah=?, alamat_rumah=?, no_telpon=?, email=?,
            tmt_masuk=?, tmt_cpns=?, jabatan=?, tmt_jabatan=?, eselon=?, unit=?,
            keterangan_mekanisme_jabatan=?,
            gol_terakhir=?, tmt_gol_terakhir=?, kp=?,
            rencana_kgb_2024=?, rencana_kgb_2025=?, rencana_kgb_2026=?,
            pendidikan_terakhir=?, program_studi_pendidikan=?, universitas=?, tahun_pendidikan=?, diklat=?,
            str_no=?, tgl_str=?, masa_berlaku=?, sip=?, status_pegawai=?, tmt_status=?, status_kepegawaian=?
        WHERE id=?
    ");

    // ===== AMBIL DATA =====
    $params = [
        val('nama'),
        val('nip'),
        val('nik'),
        val('npwp'),
        val('jenis_kelamin'),
        val('agama'),

        val('tempat_lahir'),
        val('tgl_lahir'),
        val('status_perkawinan'),
        val('nama_suami_istri'),
        val('nama_anak'),

        val('no_akte'),
        val('tgl_akta_nikah'),
        val('alamat_rumah'),
        val('no_telpon'),
        val('email'),

        val('tmt_masuk'),
        val('tmt_cpns'),
        val('jabatan'),
        val('tmt_jabatan'),
        val('eselon'),
        val('unit'),
        val('keterangan_mekanisme_jabatan'),

        val('gol_terakhir'),
        val('tmt_gol_terakhir'),
        val('kp'),

        val('rencana_kgb_2024'),
        val('rencana_kgb_2025'),
        val('rencana_kgb_2026'),

        val('pendidikan_terakhir'),
        val('program_studi_pendidikan'),
        val('universitas'),
        intval_or_null('tahun_pendidikan'),
        val('diklat'),

        val('str_no'),
        val('tgl_str'),
        val('masa_berlaku'),
        val('sip'),
        val('status_pegawai'),
        val('tmt_status'),
        val('status_kepegawaian'),
        $id
    ];

    // ===== AUTO TYPES =====
    $types = str_repeat("s", count($params));

    // ===== BIND PARAM (DINAMIS) =====
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: ../../pages/P3K-Penuh-Waktu/index.php?msg=update_success");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }

    $stmt->close();
}
