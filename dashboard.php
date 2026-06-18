<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: index.php");
  exit;
}

// base
$base_url  = '/manajemen_rs/';
$base_path = $_SERVER['DOCUMENT_ROOT'] . $base_url;

// ✅ koneksi HARUS di atas
require_once($base_path . 'config/db.php');

// QUERY NOTIF (SEMUA JENIS PEGAWAI)
$queryNotif = "
SELECT 
  SUM(CASE WHEN DATEDIFF(masa_berlaku, CURDATE()) < 0 THEN 1 ELSE 0 END) as expired,
  SUM(CASE WHEN DATEDIFF(masa_berlaku, CURDATE()) BETWEEN 0 AND 180 THEN 1 ELSE 0 END) as warning,
  SUM(CASE WHEN DATEDIFF(masa_berlaku, CURDATE()) > 180 THEN 1 ELSE 0 END) as aman
FROM (
    SELECT masa_berlaku FROM pegawai_pns
    UNION ALL
    SELECT masa_berlaku FROM pegawai_p3k_penuh_waktu
    UNION ALL
    SELECT masa_berlaku FROM pegawai_p3k_paruh_waktu
    UNION ALL
    SELECT masa_berlaku FROM pegawai_kontrak
    UNION ALL
    SELECT masa_berlaku FROM pegawai_tetap
    UNION ALL
    SELECT masa_berlaku FROM pegawai_mitra
) AS semua_pegawai
WHERE masa_berlaku IS NOT NULL
AND masa_berlaku != '0000-00-00'
";

$resultNotif = $koneksi->query($queryNotif);

if ($resultNotif && $resultNotif->num_rows > 0) {
  $data = $resultNotif->fetch_assoc();

  $expired = $data['expired'] ?? 0;
  $warning = $data['warning'] ?? 0;
  $aman = $data['aman'] ?? 0;
} else {
  $expired = 0;
  $warning = 0;
  $aman = 0;
}

// QUERY TOTAL PEGAWAI (SEMUA TABEL)
$queryTotal = "
SELECT 
  (
    (SELECT COUNT(*) FROM pegawai_p3k_paruh_waktu 
     WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') +

    (SELECT COUNT(*) FROM pegawai_tetap 
     WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') +

    (SELECT COUNT(*) FROM pegawai_kontrak 
     WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') +

    (SELECT COUNT(*) FROM pegawai_mitra 
     WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') +

    (SELECT COUNT(*) FROM pegawai_pns 
     WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') +

    (SELECT COUNT(*) FROM pegawai_p3k_penuh_waktu 
     WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF')
  ) AS total
";

$resultTotal = $koneksi->query($queryTotal);

if ($resultTotal && $resultTotal->num_rows > 0) {
  $dataTotal = $resultTotal->fetch_assoc();
  $totalPegawai = $dataTotal['total'] ?? 0;
} else {
  $totalPegawai = 0;
}

// QUERY JUMLAH PER JENIS
$queryJenis = "
SELECT 
  (SELECT COUNT(*) FROM pegawai_pns 
   WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') as pns,

  (SELECT COUNT(*) FROM pegawai_p3k_penuh_waktu 
   WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') as p3k_full,

  (SELECT COUNT(*) FROM pegawai_p3k_paruh_waktu 
   WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') as p3k_part,

  (SELECT COUNT(*) FROM pegawai_tetap 
   WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') as tetap,

  (SELECT COUNT(*) FROM pegawai_kontrak 
   WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') as kontrak,

  (SELECT COUNT(*) FROM pegawai_mitra 
   WHERE TRIM(UPPER(status_pegawai)) = 'AKTIF') as mitra
";

$resultJenis = $koneksi->query($queryJenis);

if ($resultJenis && $resultJenis->num_rows > 0) {
  $jenis = $resultJenis->fetch_assoc();

  $jmlPns = $jenis['pns'] ?? 0;
  $jmlP3kFull = $jenis['p3k_full'] ?? 0;
  $jmlP3kPart = $jenis['p3k_part'] ?? 0;
  $jmlTetap = $jenis['tetap'] ?? 0;
  $jmlKontrak = $jenis['kontrak'] ?? 0;
  $jmlMitra = $jenis['mitra'] ?? 0;
} else {
  $jmlPns = $jmlP3kFull = $jmlP3kPart = $jmlTetap = $jmlKontrak = $jmlMitra = 0;
}

// QUERY PENSIUN (PNS)
$queryPensiun = "
SELECT COUNT(*) as total
FROM (
  SELECT 
    DATE_ADD(
      tgl_lahir, 
      INTERVAL 
      (CASE 
        WHEN LOWER(IFNULL(nama_jabatan,'')) LIKE '%madya%' THEN 60
        ELSE 58
      END) YEAR
    ) AS tanggal_pensiun,

    DATEDIFF(
      DATE_ADD(
        tgl_lahir, 
        INTERVAL 
        (CASE 
          WHEN LOWER(IFNULL(nama_jabatan,'')) LIKE '%madya%' THEN 60
          ELSE 58
        END) YEAR
      ), 
      CURDATE()
    ) AS sisa_hari

  FROM pegawai_pns
  WHERE tgl_lahir IS NOT NULL
  AND tgl_lahir != '0000-00-00'
) AS data
WHERE sisa_hari BETWEEN 0 AND 365
";

$resultPensiun = $koneksi->query($queryPensiun);

if ($resultPensiun && $resultPensiun->num_rows > 0) {
  $dataPensiun = $resultPensiun->fetch_assoc();
  $pensiun = $dataPensiun['total'] ?? 0;
} else {
  $pensiun = 0;
}

$queryPendidikan = "
SELECT pendidikan,
       SUM(pns) pns,
       SUM(p3k_full) p3k_full,
       SUM(p3k_part) p3k_part,
       SUM(tetap) tetap,
       SUM(kontrak) kontrak,
       SUM(total) total
FROM (

    SELECT
        pendidikan_terakhir AS pendidikan,
        COUNT(*) pns,
        0 p3k_full,
        0 p3k_part,
        0 tetap,
        0 kontrak,
        COUNT(*) total
    FROM pegawai_pns
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'
    GROUP BY pendidikan_terakhir

    UNION ALL

    SELECT
        pendidikan_terakhir,
        0,
        COUNT(*),
        0,
        0,
        0,
        COUNT(*)
    FROM pegawai_p3k_penuh_waktu
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'
    GROUP BY pendidikan_terakhir

    UNION ALL

    SELECT
        pendidikan,
        0,
        0,
        COUNT(*),
        0,
        0,
        COUNT(*)
    FROM pegawai_p3k_paruh_waktu
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'
    GROUP BY pendidikan

    UNION ALL

    SELECT
        pendidikan,
        0,
        0,
        0,
        COUNT(*),
        0,
        COUNT(*)
    FROM pegawai_tetap
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'
    GROUP BY pendidikan

    UNION ALL

    SELECT
        pendidikan,
        0,
        0,
        0,
        0,
        COUNT(*),
        COUNT(*)
    FROM pegawai_kontrak
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'
    GROUP BY pendidikan

) x
WHERE pendidikan IS NOT NULL
AND pendidikan <> ''
GROUP BY pendidikan
ORDER BY FIELD(
    pendidikan,
    'SLTA/SMA/SMK',
    'D1',
    'D2',
    'D3',
    'D4',
    'S1',
    'S2',
    'S3'
)
";

$resultPendidikan = $koneksi->query($queryPendidikan);

$pendidikanData = [];

while ($row = $resultPendidikan->fetch_assoc()) {

  $pendidikanData[] = $row;

  $chartLabel[] = $row['pendidikan'];
  $chartData[] = (int)$row['total'];
}

$queryJabatan = "
SELECT
    UPPER(
        TRIM(
            REPLACE(
                REPLACE(jabatan,'JF ',''),
                'JF',
                ''
            )
        )
    ) AS jabatan_bersih,
    COUNT(*) AS total
FROM (

    SELECT jabatan FROM pegawai_pns
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_p3k_penuh_waktu
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_p3k_paruh_waktu
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_tetap
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_kontrak
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_mitra
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

) x
WHERE jabatan IS NOT NULL
AND jabatan <> ''
GROUP BY jabatan_bersih
ORDER BY total DESC
LIMIT 10
";

$resultJabatan = $koneksi->query($queryJabatan);

$jabatanData = [];

while ($row = $resultJabatan->fetch_assoc()) {
  $jabatanData[] = $row;
}

$queryJabatanAll = "
SELECT
    UPPER(
        TRIM(
            REPLACE(
                REPLACE(jabatan,'JF ',''),
                'JF',
                ''
            )
        )
    ) AS jabatan_bersih,
    COUNT(*) AS total
FROM (

    SELECT jabatan FROM pegawai_pns
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_p3k_penuh_waktu
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_p3k_paruh_waktu
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_tetap
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_kontrak
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

    UNION ALL

    SELECT jabatan FROM pegawai_mitra
    WHERE TRIM(UPPER(status_pegawai))='AKTIF'

) x
WHERE jabatan IS NOT NULL
AND jabatan <> ''
GROUP BY jabatan_bersih
ORDER BY jabatan_bersih ASC
";

$resultJabatanAll = $koneksi->query($queryJabatanAll);

$jabatanAll = [];

while ($row = $resultJabatanAll->fetch_assoc()) {
  $jabatanAll[] = $row;
}
?>

<?php require_once($base_path . 'layout/header.php'); ?>
<?php require_once($base_path . 'layout/sidebar.php'); ?>

<style>
  /* GLOBAL LAYOUT */
  html,
  body {
    height: 100%;
    overflow-x: hidden;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  html::-webkit-scrollbar,
  body::-webkit-scrollbar {
    display: none;
  }

  .content-wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .content {
    flex: 1;
    overflow-y: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  /* Chrome, Edge, Safari */
  .content::-webkit-scrollbar {
    width: 0px;
    height: 0px;
  }

  /* NOTIF CARD - MODERN PREMIUM */
  .notif-card-modern {
    position: relative;
    border-radius: 18px;
    padding: 15px;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #ffffff, #f9fafb);
    border: 1px solid #e5e7eb;
    height: auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 200px;
    min-height: 95px;
  }

  /* glow background */
  .notif-card-modern::before {
    content: "";
    position: absolute;
    width: 160%;
    height: 160%;
    top: -50%;
    left: -50%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.12), transparent 70%);
    transform: rotate(25deg);
    pointer-events: none;
  }

  /* shine hover */
  .notif-card-modern::after {
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: 0.5s;
  }

  .notif-card-modern:hover::after {
    left: 120%;
  }

  /* content */
  .card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  /* TEXT */
  .card-text {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: flex-end;
    max-width: 65%;
  }

  /* ANGKA */
  .card-text .num {
    font-size: 30px;
    font-weight: 800;
    color: #111827;
    line-height: 1;
  }

  /* LABEL */
  .card-text .label {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.3px;
    text-transform: none;
    opacity: 0.9;
    color: #6b7280;
    line-height: 1.2;
  }

  .card-text .label::before {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: currentColor;
    border-radius: 50%;
    margin-right: 6px;
  }

  /* WARNA SESUAI STATUS */
  .notif-card-modern.danger .label {
    color: #ef4444;
  }

  .notif-card-modern.warning .label {
    color: #f59e0b;
  }

  .notif-card-modern.success .label {
    color: #10b981;
  }

  .notif-card-modern.primary .label {
    color: #4f46e5;
  }

  .notif-card-modern.danger .num {
    text-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
  }

  .notif-card-modern.warning .num {
    text-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
  }

  .notif-card-modern.success .num {
    text-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
  }

  .notif-card-modern.primary .num {
    text-shadow: 0 0 10px rgba(79, 70, 229, 0.3);
  }

  @keyframes fadeUp {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ICON FLOAT */
  .card-icon {
    width: 35px;
    height: 35px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transform: translateY(-5px);
  }

  /* PROGRESS */
  .card-progress {
    height: 4px;
    border-radius: 10px;
    background: #e5e7eb;
    overflow: hidden;
  }

  .card-progress span {
    display: block;
    height: 100%;
    border-radius: 10px;
  }

  /* HOVER */
  .notif-card-modern:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
  }

  /* MERAH */
  .notif-card-modern.danger .card-icon {
    background: linear-gradient(135deg, #ef4444, #dc2626);
  }

  .notif-card-modern.danger .card-progress span {
    background: linear-gradient(90deg, #ef4444, #f87171);
  }

  /* ORANGE */
  .notif-card-modern.warning .card-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
  }

  .notif-card-modern.warning .card-progress span {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
  }

  /* HIJAU */
  .notif-card-modern.success .card-icon {
    background: linear-gradient(135deg, #10b981, #059669);
  }

  .notif-card-modern.success .card-progress span {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .notif-card-modern.primary .card-icon {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
  }

  .notif-card-modern.primary .card-progress span {
    background: linear-gradient(90deg, #6366f1, #4f46e5);
  }

  /* JENIS PEGAWAI */
  .jenis-wrapper {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
    padding: 24px;
  }

  .jenis-wrapper-notif {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
    padding: 24px;
    margin-bottom: 8px;
  }

  .jenis-header h5 {
    font-weight: 600;
    margin-bottom: 4px;
  }

  .jenis-header span {
    font-size: 12px;
    color: #6b7280;
  }

  /* grid */
  .jenis-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-top: 10px;
  }

  /* CARD BASE */
  .jenis-card {
    position: relative;
    overflow: hidden;
    height: 170px;
    padding: 15px;
    border-radius: 18px;
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: auto;
    cursor: pointer;
  }

  .jenis-card:active {
    transform: scale(0.97);
  }

  /* glow subtle */
  .jenis-card::before {
    content: "";
    position: absolute;
    width: 150%;
    height: 150%;
    top: -40%;
    left: -40%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.07), transparent 70%);
    pointer-events: none;
  }

  .jenis-card::after {
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 100%;
    height: 100%;
    background: linear-gradient(110deg,
        transparent 40%,
        rgba(255, 255, 255, 0.25) 50%,
        transparent 60%);
    transition: 0.6s;
    pointer-events: none;
  }

  .jenis-card:hover::after {
    left: 120%;
  }

  /* hover */
  .jenis-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
  }

  /* ICON */
  .jenis-icon {
    width: 35px;
    height: 35px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: #fff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    margin-top: 1px;
  }

  .jenis-card:hover .jenis-icon {
    transform: translateY(-6px) scale(1.05);
  }

  /* TITLE */
  .jenis-title {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* PROGRESS */
  .jenis-progress {
    flex: 1;
    height: 5px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
  }

  /* PERSEN */
  .jenis-percent {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    min-width: 32px;
    text-align: right;
  }

  .jenis-card:hover .jenis-title {
    color: #4f46e5;
  }

  /* DEFAULT */
  .jenis-progress span {
    display: block;
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, #4f46e5, #6366f1);
    transition: width 0.4s ease;
  }

  /* PNS */
  .jenis-card.pns .jenis-progress span {
    background: linear-gradient(90deg, #0ea5e9, #38bdf8);
  }

  /* P3K FULL */
  .jenis-card.p3kfull .jenis-progress span {
    background: linear-gradient(90deg, #22c55e, #4ade80);
  }

  /* P3K PART */
  .jenis-card.p3kpart .jenis-progress span {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
  }

  /* TETAP */
  .jenis-card.tetap .jenis-progress span {
    background: linear-gradient(90deg, #6366f1, #818cf8);
  }

  /* KONTRAK */
  .jenis-card.kontrak .jenis-progress span {
    background: linear-gradient(90deg, #ef4444, #f87171);
  }

  /* MITRA */
  .jenis-card.mitra .jenis-progress span {
    background: linear-gradient(90deg, #a855f7, #c084fc);
  }

  /* PRIMARY */
  .jenis-card.primary .jenis-icon {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
  }

  /* PNS */
  .jenis-card.pns .jenis-icon {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8);
  }

  /* P3K FULL */
  .jenis-card.p3kfull .jenis-icon {
    background: linear-gradient(135deg, #22c55e, #4ade80);
  }

  /* P3K PART */
  .jenis-card.p3kpart .jenis-icon {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
  }

  /* TETAP */
  .jenis-card.tetap .jenis-icon {
    background: linear-gradient(135deg, #6366f1, #818cf8);
  }

  /* KONTRAK */
  .jenis-card.kontrak .jenis-icon {
    background: linear-gradient(135deg, #ef4444, #f87171);
  }

  /* MITRA */
  .jenis-card.mitra .jenis-icon {
    background: linear-gradient(135deg, #a855f7, #c084fc);
  }

  .jenis-progress span {
    display: block;
    height: 100%;
    border-radius: 10px;
    transition: width 0.4s ease;
  }

  /* MODAL */
  .modal-header.custom-header {
    position: relative;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    padding: 18px 20px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .modal-header.custom-header::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(to right, #22d3ee, #a78bfa);
    opacity: 0.8;
  }

  .modal-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .modal-title i {
    background: rgba(255, 255, 255, 0.2);
    padding: 6px;
    border-radius: 8px;
    font-size: 14px;
  }

  .close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .close-btn:hover {
    background: #ef4444;
    transform: rotate(90deg) scale(1.05);
    box-shadow: 0 6px 15px rgba(239, 68, 68, 0.4);
  }

  .modal-dialog {
    max-width: 900px;
    display: flex;
    align-items: center;
  }

  .modal-body {
    background: #f9fafb;
    padding: 18px;
    max-height: 75vh;
    overflow-y: auto;
  }

  .modal-content {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
  }

  /* HEADER DASHBOARD */
  .content-header {
    padding-top: 10px;
    padding-bottom: 10px;
  }

  .jenis-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .jenis-badge {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    box-shadow: 0 8px 18px rgba(79, 70, 229, 0.3);
  }

  .jenis-header h5 {
    font-size: 17px;
    font-weight: 800;
    margin: 0;
    color: #111827;
  }

  .jenis-header span {
    font-size: 12px;
    color: #6b7280;
    display: block;
    margin-top: 2px;
  }

  /* TOP */
  .card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  /* BOTTOM */
  .card-bottom {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* PROGRESS */
  .card-progress {
    flex: 1;
    height: 5px;
    border-radius: 10px;
    background: #e5e7eb;
    overflow: hidden;
  }

  /* PERSENTASE */
  .card-percent {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    min-width: 35px;
    text-align: right;
  }

  /* TITLE DI BAWAH */
  .jenis-title-below {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    transition: 0.3s;
    margin-top: 4px;
  }

  /* HEADER (ICON + VALUE) */
  .jenis-header-card {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* LEFT SIDE (ICON + TITLE) */
  .jenis-header-card .left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* VALUE */
  .jenis-value {
    font-size: 30px;
    font-weight: 800;
    color: #111827;
  }

  /* FOOTER */
  .jenis-footer {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .jenis-card:hover .jenis-title-below {
    color: #4f46e5;
    transform: translateY(-2px);
  }

  .row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }

  .notif-col {
    flex: 0 0 auto;
  }

  .notif-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, 215px);
    margin-top: 10px;
  }

  .pendidikan-wrapper {
    background: #fff;
    border-radius: 20px;
    padding: 24px;
    margin-top: 8px;
    margin-bottom: 8px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
  }

  /* =========================
   TABLE PENDIDIKAN PREMIUM
========================= */

  .table-pendidikan {
    width: 100%;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
    font-size: 12px;
    border-radius: 14px;
  }

  .table-pendidikan th:first-child,
  .table-pendidikan td:first-child {
    width: 20%;
  }

  .table-pendidikan th:not(:first-child),
  .table-pendidikan td:not(:first-child) {
    width: 20%;
  }

  /* HEADER */
  .table-pendidikan thead th {
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    color: white;
    text-align: center;
    font-weight: 700;
    white-space: nowrap;
    border-right: 1px solid rgba(255, 255, 255, .2);
    padding: 8px 6px;
    font-size: 12px;
  }

  /* BODY */
  .table-pendidikan tbody td {
    text-align: center;
    vertical-align: middle;
    font-weight: 600;
    color: #374151;
    border-right: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    padding: 8px 6px;
    font-size: 12px;
  }

  /* PENDIDIKAN */
  .table-pendidikan tbody td:first-child {
    text-align: left;
    padding-left: 20px;
    font-weight: 700;
    color: #111827;
  }

  .table-pendidikan th,
  .table-pendidikan td {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
  }

  /* ZEBRA */
  .table-pendidikan tbody tr:nth-child(even) {
    background: #f8fafc;
  }

  .table-pendidikan tbody tr:nth-child(odd) {
    background: #ffffff;
  }

  /* HOVER */
  .table-pendidikan tbody tr {
    transition: all .25s ease;
  }

  .table-pendidikan tbody tr:hover {
    background: #eef2ff;
    transform: scale(1.003);
  }

  /* ANGKA */
  .table-pendidikan tbody td:not(:first-child) {
    font-weight: 700;
  }

  /* TOTAL BADGE */
  .badge-total {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(34, 197, 94, .25);
    min-width: 38px;
    height: 26px;
    font-size: 11px;
    padding: 0 8px;
  }


  /* RESPONSIVE */
  .badge-pendidikan {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 999px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
  }

  .btn-modern.success {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 10px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    box-shadow: 0 8px 18px rgba(16, 185, 129, 0.25);
    transition: 0.2s;
  }

  .btn-modern.success:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(16, 185, 129, 0.35);
  }

  .jabatan-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
  }

  @media(max-width:768px) {
    .jabatan-list {
      grid-template-columns: 1fr;
    }
  }

  .jabatan-item {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 8px 12px;
    transition: .25s;
  }

  .jabatan-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
  }

  .jabatan-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
  }

  .jabatan-name {
    color: #374151;
    font-size: 11px;
    font-weight: 700;
  }

  .jabatan-progress {
    height: 5px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
    margin-top: 6px;
  }

  .jabatan-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5, #06b6d4);
    border-radius: 999px;
  }

  .badge-total {
    height: 22px;
    min-width: 34px;
    font-size: 10px;
  }

  #modalJabatan .modal-dialog {
    margin: 40px auto;
  }

  #modalJabatan .modal-body {
    max-height: calc(100vh - 160px);
    overflow-y: auto;
    padding-bottom: 40px;
  }

  #modalJabatan .modal-content {
    border-radius: 16px;
    overflow: hidden;
  }

  #modalJabatan table {
    border-radius: 12px;
    overflow: hidden;
  }

  #modalJabatan thead th {
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    color: #fff;
    border: none;
    font-size: 13px;
    font-weight: 700;
  }

  #modalJabatan tbody tr {
    transition: .2s;
  }

  #modalJabatan tbody tr:hover {
    background: #eef2ff;
  }

  #modalJabatan tbody td {
    vertical-align: middle;
    font-size: 13px;
  }

  #modalJabatan .badge-light {
    background: #eef2ff;
    color: #4f46e5;
    font-weight: 700;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="jenis-header">
        <div class="jenis-title-wrap">
          <div class="jenis-badge">
            <i class="fas fa-chart-pie"></i>
          </div>
          <div>
            <h5>Dashboard Pegawai</h5>
            <span>Ringkasan berdasarkan jenis kepegawaian</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="jenis-wrapper-notif">
        <div class="jenis-header">
          <h5>Monitoring Status Pegawai</h5>
          <span>Notifikasi masa berlaku SIP, tahun lulus, dan data penting lainnya</span>
        </div>
        <div class="notif-grid">
          <?php
          function cardNotif($class, $num, $label, $icon, $onclick = '', $total = 100)
          {
            $percent = $total > 0 ? round(($num / $total) * 100) : 0;
            return "
            <div class='notif-col'>
              <div class='notif-card-modern $class' $onclick>
                <div class='card-top'>
             
                  <div class='card-icon'>
                    <i class='fas $icon'></i>
                  </div>
               
                  <div class='card-text' style='text-align:right'>
                    <span class='label'>$label</span>
                    <span class='num'>$num</span>
                  </div>
                </div>
                <div class='card-bottom'>
                  <div class='card-progress'>
                    <span style='width: {$percent}%'></span>
                  </div>
                  <div class='card-percent'>
                    {$percent}%
                  </div>
                </div>
              </div>
            </div>";
          }
          $totalNotif = $expired + $warning + $aman;
          echo cardNotif('primary', $pensiun, 'PENSIUN', 'fa-user-clock', "onclick=\"loadNotif('pensiun')\"", $totalPegawai);
          echo cardNotif('danger', $expired, 'SIP HABIS', 'fa-exclamation-triangle', "onclick=\"loadNotif('expired')\"", $totalNotif);
          echo cardNotif('warning', $warning, 'SIP ≤ 6 Bulan', 'fa-clock', "onclick=\"loadNotif('warning')\"", $totalNotif);
          echo cardNotif('success', $aman, 'SIP > 6 Bulan', 'fa-check-circle', "onclick=\"loadNotif('aman')\"", $totalNotif);
          ?>
        </div>
      </div>
      <!-- JENIS PEGAWAI (GLASS GRID) -->
      <div class="jenis-wrapper">
        <div class="d-flex justify-content-between align-items-center">
          <div class="jenis-header">
            <h5>Distribusi Pegawai</h5>
            <span>Komposisi pegawai berdasarkan jenis kepegawaian</span>
          </div>

          <a href="./export_all_pegawai.php?jenis=semua_pegawai"
            class="btn-modern success"
            target="_blank"
            rel="noopener noreferrer">
            <i class="fas fa-download"></i>
            <span>Export Semua Pegawai</span>
          </a>
        </div>
        <div class="jenis-row">
          <?php
          function cardJenis($title, $num, $icon, $class, $total = 0, $url = '#')
          {
            $percent = $total > 0 ? round(($num / $total) * 100) : 0;
            return "
            <div class='jenis-card $class' onclick=\"window.location.href='$url'\">
              <div class='jenis-header-card'>
                <div class='jenis-icon'>
                  <i class='fas $icon'></i>
                </div>
                <div class='jenis-value'>
                  $num
                </div>
              </div>
              <div class='jenis-title-below'>
                $title
              </div>
              <div class='jenis-footer'>
                <div class='jenis-progress'>
                  <span style='width: {$percent}%'></span>
                </div>
                <span class='jenis-percent'>{$percent}%</span>
              </div>
            </div>";
          }
          echo cardJenis('Total Pegawai', $totalPegawai, 'fa-users', 'primary', $totalPegawai, '');
          echo cardJenis('Pegawai Negeri Sipil', $jmlPns, 'fa-user-tie', 'pns', $totalPegawai, 'pages/PNS');
          echo cardJenis('PPPK Penuh Waktu', $jmlP3kFull, 'fa-user-check', 'p3kfull', $totalPegawai,  'pages/P3K-Penuh-Waktu');
          echo cardJenis('PPPK Paruh Waktu', $jmlP3kPart, 'fa-user-clock', 'p3kpart', $totalPegawai, 'pages/P3K-Paruh-Waktu');
          echo cardJenis('Pegawai Tetap', $jmlTetap, 'fa-id-card', 'tetap', $totalPegawai, 'pages/Tetap');
          echo cardJenis('Pegawai Kontrak', $jmlKontrak, 'fa-file-contract', 'kontrak', $totalPegawai, 'pages/Kontrak');
          echo cardJenis('Pegawai Mitra', $jmlMitra, 'fa-handshake-angle', 'mitra', $totalPegawai, 'pages/Mitra');          ?>
        </div>
      </div>
      <div class="pendidikan-wrapper">

        <div class="jenis-header mb-2">
          <h5>Detail Pendidikan Pegawai</h5>
          <span>Rincian per jenis kepegawaian</span>
        </div>

        <div>
          <table class="table table-bordered table-hover table-pendidikan">
            <thead>
              <tr>
                <th>Pendidikan</th>
                <th>PNS</th>
                <th>PPPK PENUH WAKTU</th>
                <th>PPPK PARUH WAKTU</th>
                <th>Tetap</th>
                <th>Kontrak</th>
                <th>Total</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($pendidikanData as $row): ?>
                <tr>
                  <td>
                    <strong><?= $row['pendidikan'] ?></strong>
                  </td>
                  <td><?= $row['pns'] ?></td>
                  <td><?= $row['p3k_full'] ?></td>
                  <td><?= $row['p3k_part'] ?></td>
                  <td><?= $row['tetap'] ?></td>
                  <td><?= $row['kontrak'] ?></td>
                  <td>
                    <span class="badge-total">
                      <?= $row['total'] ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="pendidikan-wrapper">

        <div class="d-flex justify-content-between align-items-center mb-3">

          <div class="jenis-header">
            <h5>Top 10 Jabatan</h5>
            <span>Jabatan dengan jumlah pegawai terbanyak</span>
          </div>

          <a
            class="btn-modern success"
            data-toggle="modal"
            data-target="#modalJabatan">
            <i class="fas fa-list"></i>
            Lihat Semua
          </a>

        </div>

        <div class="jabatan-list">

          <?php foreach ($jabatanData as $j): ?>

            <div class="jabatan-item">

              <div class="jabatan-head">
                <span class="jabatan-name">
                  <?= htmlspecialchars($j['jabatan_bersih']) ?>
                </span>

                <span class="badge-total">
                  <?= $j['total'] ?>
                </span>
              </div>

              <div class="jabatan-progress">
                <div class="jabatan-progress-bar"
                  style="width:<?= ($j['total'] / $jabatanData[0]['total']) * 100 ?>%">
                </div>
              </div>

            </div>

          <?php endforeach; ?>

        </div>

      </div>

    </div>
  </section>


  <script>
    function loadNotif(type) {
      let title = '';
      let url = '';
      if (type === 'expired') {
        title = '<i class="fas fa-exclamation-circle"></i> SIP HABIS';
        url = 'notifSIP.php?type=expired';
      } else if (type === 'warning') {
        title = '<i class="fas fa-clock"></i> SIP Kurang Dari 6 Bulan';
        url = 'notifSIP.php?type=warning';
      } else if (type === 'pensiun') {
        title = '<i class="fas fa-user-clock"></i> Mendekati Pensiun';
        url = 'notifPensiun.php';
      } else {
        title = '<i class="fas fa-check-circle"></i> SIP > 6 Bulan';
        url = 'notifSIP.php?type=aman';
      }
      document.getElementById('notifTitle').innerHTML = title;
      fetch(url)
        .then(res => res.text())
        .then(data => {
          document.getElementById('notifContent').innerHTML = data;
          $('#notifModal').modal('show');
        });
    }
  </script>
</div>
<div class="modal fade" id="notifModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header custom-header">
        <h5 class="modal-title" id="notifTitle">
          <i class="fas fa-bell"></i> Notifikasi Pegawai
        </h5>
        <button type="button" class="close-btn" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="notifContent">
        Loading...
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalJabatan">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header custom-header">
        <h5 class="modal-title">
          <i class="fas fa-briefcase"></i>
          Semua Jabatan Pegawai
        </h5>

        <button
          type="button"
          class="close-btn"
          data-dismiss="modal">
          &times;
        </button>
      </div>

      <div class="modal-body p-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-primary text-white">
              <tr>
                <th width="70">No</th>
                <th>Jabatan</th>
                <th width="120" class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach ($jabatanAll as $j): ?>
                <tr class="table table-bordered">
                  <td class="text-center">
                    <span class="badge badge-light px-3 py-2">
                      <?= $no++ ?>
                    </span>
                  </td>
                  <td>
                    <i class="text-primary mr-2"></i>
                    <?= htmlspecialchars($j['jabatan_bersih']) ?>
                  </td>
                  <td class="text-center">
                    <span class="badge-total">
                      <?= $j['total'] ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
<?php require_once($base_path . 'layout/footer.php'); ?>