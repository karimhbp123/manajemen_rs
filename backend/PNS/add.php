<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

$base_url  = '/manajemen_rs/';
$base_path = $_SERVER['DOCUMENT_ROOT'] . $base_url;

require_once($base_path . 'config/db.php');

// ambil data POST (aman)
$nama = $_POST['nama'] ?? '';
$nip = $_POST['nip'] ?? '';
$nik = $_POST['nik'] ?? '';
$npwp = $_POST['npwp'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$agama = $_POST['agama'] ?? '';
$tempat_lahir = $_POST['tempat_lahir'] ?? '';
$tgl_lahir = $_POST['tgl_lahir'] ?? '';
$status_perkawinan = $_POST['status_perkawinan'] ?? '';
$nama_suami_istri = $_POST['nama_suami_istri'] ?? '';
$nama_anak = $_POST['nama_anak'] ?? '';
$no_akte = $_POST['no_akte'] ?? '';
$tgl_akta_nikah = $_POST['tgl_akta_nikah'] ?? '';
$alamat = $_POST['alamat_rumah'] ?? '';
$no_hp = $_POST['no_telpon'] ?? '';
$email = $_POST['email'] ?? '';
$tmt_masuk = $_POST['tmt_masuk'] ?? '';
$tmt_cpns = $_POST['tmt_cpns'] ?? '';
$jabatan = $_POST['jabatan'] ?? '';
$tmt_jabatan = $_POST['tmt_jabatan'] ?? '';
$eselon = $_POST['eselon'] ?? '';
$unit = $_POST['unit'] ?? '';
$keterangan_mekanisme_jabatan = $_POST['keterangan_mekanisme_jabatan'] ?? '';
$gol_terakhir = $_POST['gol_terakhir'] ?? '';
$tmt_gol_terakhir = $_POST['tmt_gol_terakhir'] ?? '';
$kp = $_POST['kp'] ?? '';
$rencana_kgb_2024 = $_POST['rencana_kgb_2024'] ?? '';
$rencana_kgb_2025 = $_POST['rencana_kgb_2025'] ?? '';
$rencana_kgb_2026 = $_POST['rencana_kgb_2026'] ?? '';
$pendidikan_terakhir = $_POST['pendidikan_terakhir'] ?? '';
$program_studi = $_POST['program_studi_pendidikan'] ?? '';
$universitas = $_POST['universitas'] ?? '';
$tahun_pendidikan = $_POST['tahun_pendidikan'] ?? '';
$diklat = $_POST['diklat'] ?? '';
$str_no = $_POST['str_no'] ?? '';
$tgl_str = $_POST['tgl_str'] ?? '';
$masa_berlaku = $_POST['masa_berlaku'] ?? '';
$sip = $_POST['sip'] ?? '';
$status_pegawai = $_POST['status_pegawai'] ?? '';
$tmt_status = $_POST['tmt_status'] ?? '';

// validasi
if ($nama == '') {
    echo "Nama wajib diisi!";
    exit;
}

// query insert
$query = "INSERT INTO pegawai_pns (
    nama, nip, nik, npwp, jenis_kelamin, agama,
    tempat_lahir, tgl_lahir, status_perkawinan,
    nama_suami_istri, nama_anak, no_akte, tgl_akta_nikah,
    alamat_rumah, no_telpon, email,
    tmt_masuk, tmt_cpns, jabatan, tmt_jabatan,
    eselon, unit, keterangan_mekanisme_jabatan,
    gol_terakhir, tmt_gol_terakhir, kp,
    rencana_kgb_2024, rencana_kgb_2025, rencana_kgb_2026,
    pendidikan_terakhir, program_studi_pendidikan, universitas, tahun_pendidikan,
    diklat, str_no, tgl_str, masa_berlaku, sip, status_pegawai, tmt_status
) VALUES (
    '$nama', '$nip', '$nik', '$npwp', '$jenis_kelamin', '$agama',
    '$tempat_lahir', '$tgl_lahir', '$status_perkawinan',
    '$nama_suami_istri', '$nama_anak', '$no_akte', '$tgl_akta_nikah',
    '$alamat', '$no_hp', '$email',
    '$tmt_masuk', '$tmt_cpns', '$jabatan', '$tmt_jabatan',
    '$eselon', '$unit', '$keterangan_mekanisme_jabatan',
    '$gol_terakhir', '$tmt_gol_terakhir', '$kp',
    '$rencana_kgb_2024', '$rencana_kgb_2025', '$rencana_kgb_2026',
    '$pendidikan_terakhir', '$program_studi', '$universitas', '$tahun_pendidikan',
    '$diklat', '$str_no', '$tgl_str', '$masa_berlaku', '$sip', '$status_pegawai', '$tmt_status'
)";

if ($koneksi->query($query)) {
    header("Location: {$base_url}pages/PNS/index.php?msg=add_success");
    exit;
} else {
    echo "Gagal menyimpan data: " . $koneksi->error;
}
