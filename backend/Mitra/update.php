<?php
require_once('../../config/db.php');

$id = intval($_POST['id']);
$nama = $_POST['nama'];
$tmt = $_POST['tmt'];
$jabatan = $_POST['jabatan'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$unit = $_POST['unit'];
$alamat = $_POST['alamat'];
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];
$status_pegawai = $_POST['status_pegawai'];
$masa_berlaku = $_POST['masa_berlaku'];
$status_kepegawaian = 'PEGAWAI MITRA';
$tmt_status = $_POST['tmt_status'] ?? null;

// LOGIC: kalau AKTIF → tmt_status NULL
if ($status_pegawai == 'AKTIF') {
    $tmt_status = NULL;
} else {
    $tmt_status = !empty($tmt_status) ? $tmt_status : NULL;
}

$stmt = $koneksi->prepare("UPDATE pegawai_mitra SET 
  nama=?, 
  tmt=?, 
  jabatan=?, 
  jenis_kelamin=?, 
  unit=?, 
  alamat=?, 
  no_hp=?, 
  email=?,
  status_pegawai=?,
  status_kepegawaian=?,
  masa_berlaku=?,
  tmt_status=?
  WHERE id=?");

$stmt->bind_param(
  "ssssssssssssi",
  $nama,
  $tmt,
  $jabatan,
  $jenis_kelamin,
  $unit,
  $alamat,
  $no_hp,
  $email,
  $status_pegawai,
  $status_kepegawaian,
  $masa_berlaku,
  $tmt_status,
  $id
);

if ($stmt->execute()) {
  header("Location: ../../pages/Mitra/index.php?msg=update_success");
  exit;
} else {
  echo "Gagal update: " . $stmt->error;
}
