<?php
require_once('../../config/db.php');

function formatTanggal($tgl)
{
    return (empty($tgl) || $tgl == '0000-00-00')
        ? '-'
        : date('d-m-Y', strtotime($tgl));
}
$id = intval($_GET['id']);
$data = $koneksi->query("SELECT * FROM pegawai_pns WHERE id=$id")->fetch_assoc();
$tgl_lahir = $data['tgl_lahir'];
$lahir = new DateTime($tgl_lahir);
$today = new DateTime();
$diff = $today->diff($lahir);
$usia_tahun = $diff->y;
$usia_bulan = $diff->m;
$tahun_lahir = $lahir->format('Y');
if (!empty($data['tmt_jabatan'])) {
    $tmt = new DateTime($data['tmt_jabatan']);
    $today = new DateTime();
    $diff = $today->diff($tmt);
    $masa_tahun = $diff->y;
    $masa_bulan = $diff->m;
} else {
    $masa_tahun = '-';
    $masa_bulan = '';
}
if (!empty($data['kp']) && $data['kp'] != '0000-00-00') {
    $kp = new DateTime($data['kp']);
    $today = new DateTime();
    $diff_kp = $today->diff($kp);
    $kp_tahun = $diff_kp->y;
    $kp_bulan = $diff_kp->m;
} else {
    $kp_tahun = '-';
    $kp_bulan = '';
}
?>

<style>
    .modal-dialog {
        max-width: 1000px;
    }

    .detail-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        align-items: start;
        overflow-x: hidden;

    }

    .card-box {
        width: 100%;
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

    .card-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
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
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 1px 6px;
        margin-bottom: 8px;
        border-radius: 10px;
        transition: 0.2s;
    }

    .item:hover {
        background: #f8fafc;
    }

    /* LABEL */
    .label {
        font-size: 12px;
        color: #334155;
        font-weight: 500;
        min-width: 140px;
        max-width: 150px;
        line-height: 1.4;
        padding: 9px 12px;
    }

    /* VALUE (INI KUNCI PERBAIKAN) */
    .value {
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        flex: 1;
        min-width: 0;
        text-align: left;
        line-height: 1.5;
        padding: 8px 12px;
        border-radius: 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        word-break: break-word;
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
        <div class="section-title">👤 Data Pribadi</div>

        <div class="item">
            <span class="label">NIP</span>
            <span class="value"><?= $data['nip']; ?></span>
        </div>

        <div class="item">
            <span class="label">NIK</span>
            <span class="value"><?= $data['nik']; ?></span>
        </div>

        <div class="item">
            <span class="label">NPWP</span>
            <span class="value"><?= $data['npwp']; ?></span>
        </div>

        <div class="item">
            <span class="label">Agama</span>
            <span class="value"><?= $data['agama']; ?></span>
        </div>

        <div class="item">
            <span class="label">Tanggal Lahir</span>
            <span class="value">
                <?= $data['tempat_lahir']; ?>, <?= date('d-m-Y', strtotime($data['tgl_lahir'])); ?>
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
            <span class="label">Status Perkawinan</span>
            <span class="value"><?= $data['status_perkawinan']; ?></span>
        </div>

        <div class="item">
            <span class="label">Nama Suami/Istri</span>
            <span class="value"><?= $data['nama_suami_istri']; ?></span>
        </div>

        <div class="item">
            <span class="label">Nama Anak</span>
            <span class="value"><?= $data['nama_anak']; ?></span>
        </div>

        <div class="item">
            <span class="label">No Akta Nikah</span>
            <span class="value"><?= $data['no_akte']; ?></span>
        </div>

        <div class="item">
            <span class="label">Tanggal Akta Nikah</span>
            <span class="value"><?= formatTanggal($data['tgl_akta_nikah']); ?></span>
        </div>


    </div>

    <!-- STATUS & JABATAN -->
    <div class="card-box">
        <div class="section-title">💼 Status & Jabatan</div>

        <div class="item">
            <span class="label">TMT Jabatan</span>
            <span class="value"><?= date('d-m-Y', strtotime($data['tmt_jabatan'])); ?></span>
        </div>

        <div class="item">
            <span class="label">Masa Jabatan</span>
            <span class="value highlight">
                <?= $masa_tahun; ?> Tahun <?= $masa_bulan; ?> Bulan
            </span>
        </div>

        <div class="item">
            <span class="label">Golongan</span>
            <span class="value"><?= $data['gol_terakhir']; ?></span>
        </div>

        <div class="item">
            <span class="label">TMT Golongan</span>
            <span class="value"><?= date('d-m-Y', strtotime($data['tmt_gol_terakhir'])); ?></span>
        </div>

        <div class="item">
            <span class="label">Tanggal KP</span>
            <span class="value"><?= date('d-m-Y', strtotime($data['kp'])); ?></span>
        </div>

        <div class="item">
            <span class="label">Masa KP</span>
            <span class="value highlight">
                <?= $kp_tahun; ?> Tahun <?= $kp_bulan; ?> Bulan
            </span>
        </div>

        <div class="item">
            <span class="label">Rencana KGB 2024</span>
            <span class="value"><?= formatTanggal($data['rencana_kgb_2024']); ?></span>
        </div>

        <div class="item">
            <span class="label">Rencana KGB 2025</span>
            <span class="value"><?= formatTanggal($data['rencana_kgb_2025']); ?></span>
        </div>


        <div class="item">
            <span class="label">Rencana KGB 2026</span>
            <span class="value"><?= formatTanggal($data['rencana_kgb_2026']); ?></span>
        </div>

        <div class="item">
            <span class="label">Eselon</span>
            <span class="value"><?= $data['eselon']; ?></span>
        </div>

        <div class="item">
            <span class="label">Status Kepegawaian</span>
            <span class="value"><?= $data['status_kepegawaian']; ?></span>
        </div>
    </div>

    <!-- PENDIDIKAN -->
    <div class="card-box">
        <div class="section-title">🎓 Pendidikan</div>


        <div class="item">
            <span class="label">Pendidikan Terakhir</span>
            <span class="value"><?= $data['pendidikan_terakhir']; ?></span>
        </div>

        <div class="item">
            <span class="label">Program Studi</span>
            <span class="value"><?= $data['program_studi_pendidikan']; ?></span>
        </div>

        <div class="item">
            <span class="label">Universitas</span>
            <span class="value"><?= $data['universitas']; ?></span>
        </div>

        <div class="item">
            <span class="label">Pelatihan/Seminar</span>
            <span class="value"><?= $data['diklat']; ?></span>
        </div>
    </div>

    <!-- DOKUMEN -->
    <div class="card-box">
        <div class="section-title">📄 Dokumen</div>

        <div class="item">
            <span class="label">STR</span>
            <span class="value"><?= $data['str_no']; ?></span>
        </div>

        <div class="item">
            <span class="label">Tanggal STR</span>
            <span class="value"><?= formatTanggal($data['tgl_str']); ?></span>
        </div>

        <div class="item">
            <span class="label">Berlaku Sampai</span>
            <span class="value"><?= formatTanggal($data['masa_berlaku']); ?></span>
        </div>

        <div class="item">
            <span class="label">SIP</span>
            <span class="value"><?= $data['sip']; ?></span>
        </div>
    </div>

</div>