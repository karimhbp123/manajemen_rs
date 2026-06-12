<?php
require_once('../../config/db.php');


$id = intval($_GET['id']);
$data = $koneksi->query("SELECT * FROM pegawai_kontrak WHERE id=$id")->fetch_assoc();

$tgl_lahir = $data['tanggal_lahir'];

$lahir = new DateTime($tgl_lahir);
$today = new DateTime();

$diff = $today->diff($lahir);

$usia_tahun = $diff->y;
$usia_bulan = $diff->m;

$tahun_lahir = $lahir->format('Y');

// MASA KEPEGAWAIAN
if (!empty($data['tmt_kepegawaian']) && $data['tmt_kepegawaian'] != '0000-00-00') {
    $tmt_kepeg = new DateTime($data['tmt_kepegawaian']);
    $today = new DateTime();

    $diff_kepeg = $today->diff($tmt_kepeg);

    $kepeg_tahun = $diff_kepeg->y;
    $kepeg_bulan = $diff_kepeg->m;
} else {
    $kepeg_tahun = '-';
    $kepeg_bulan = '';
}

?>

<style>
    .modal-dialog {
        max-width: 900px;
        margin: auto;
    }

    .detail-container {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .card-box {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        transition: 0.2s;
        height: fit-content;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 18px;
        padding: 22px 24px;
        border: 1px solid #eef2f7;
        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    @media (max-width: 768px) {
        .detail-container {
            flex-direction: column;
        }
    }

    .card-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e7eb;
        position: relative;
    }

    /* aksen garis premium */
    .section-title::before {
        content: "";
        width: 4px;
        height: 18px;
        border-radius: 6px;
        background: linear-gradient(180deg, #6366f1, #3b82f6);
    }

    .item {
        display: grid;
        margin-bottom: 12px;
        gap: 12px;
        align-items: center;
        grid-template-columns: 35% 65%;
    }

    .item:hover {
        background: #f8fafc;
    }

    /* LABEL */
    .label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        line-height: 1.4;
        flex-shrink: 0;
        width: 120px;
        padding: 9px 12px;
        white-space: nowrap;
    }

    /* VALUE (INI KUNCI PERBAIKAN) */
    .value {
        flex: 1;
        min-width: 0;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        padding: 10px 14px;
        border-radius: 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        word-break: normal;
        overflow-wrap: anywhere;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-warning {
        background: #fef9c3;
        color: #854d0e;
    }

    .highlight {
        background: #f8fafc;
        padding: 10px;
        border-radius: 10px;
    }
</style>

<div class="detail-container">
    <!-- DATA PRIBADI -->
    <div class="card-box">
        <div class="section-title">👤 Identitas Pribadi</div>

        <div class="item">
            <span class="label">NIRP</span>
            <span class="value"><?= $data['nip']; ?></span>
        </div>

        <div class="item">
            <span class="label">NIK</span>
            <span class="value"><?= $data['nik']; ?></span>
        </div>

        <div class="item">
            <span class="label">Tanggal Lahir</span>
            <span class="value">
                <?= $data['tempat_lahir']; ?>, <?= date('d-m-Y', strtotime($data['tanggal_lahir'])); ?>
            </span>
        </div>

        <div class="item">
            <span class="label">Usia</span>
            <span class="value">
                <?= $usia_tahun; ?> Tahun <?= $usia_bulan; ?> Bulan
                <small>(<?= $tahun_lahir; ?>)</small>
            </span>
        </div>
        <div class="item">
            <span class="label">Agama</span>
            <span class="value"><?= $data['agama']; ?></span>
        </div>
    </div>

    <div class="card-box">
        <div class="section-title">💼 Pendidikan & Kepegawaian</div>
        <div class="item">
            <span class="label">Pendidikan</span>
            <span class="value"><?= $data['pendidikan']; ?></span>
        </div>

        <div class="item">
            <span class="label">Program Studi</span>
            <span class="value"><?= $data['program_studi']; ?></span>
        </div>

        <div class="item">
            <span class="label">Status Kepegawaian</span>
            <span class="value"><?= $data['status_kepegawaian']; ?></span>
        </div>

        <div class="item">
            <span class="label">TMT Kepegawaian</span>
            <span class="value">
                <?= date('d-m-Y', strtotime($data['tmt_kepegawaian'])); ?>
            </span>
        </div>

        <div class="item">
            <span class="label">Masa Kepegawaian</span>
            <span class="value">
                <?= $kepeg_tahun; ?> Tahun <?= $kepeg_bulan; ?> Bulan
            </span>
        </div>

        <div class="item">
            <span class="label">Masa Berlaku SIP</span>
            <span class="value">
                <?=
                (!empty($data['masa_berlaku']) &&
                    $data['masa_berlaku'] != '0000-00-00')
                    ? date('d-m-Y', strtotime($data['masa_berlaku']))
                    : '-';
                ?>
            </span>
        </div>
    </div>
</div>