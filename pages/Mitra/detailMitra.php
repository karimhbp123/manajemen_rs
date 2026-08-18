<?php
require_once('../../config/db.php');


$id = intval($_GET['id']);
$data = $koneksi->query("SELECT * FROM pegawai_mitra WHERE id=$id")->fetch_assoc();


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
    /* ================================
       MODAL DETAIL PEGAWAI MITRA
    ================================= */

    .modal-dialog {
        max-width: 600px;
        width: calc(100% - 30px);
        margin: 1.75rem auto;
    }

    /* Agar modal tetap nyaman di tengah */
    .modal-dialog.modal-dialog-centered {
        min-height: calc(100% - 3.5rem);
        display: flex;
        align-items: center;
    }

    .modal-content {
        width: 100%;
        border: 0;
        border-radius: 18px;
        overflow: hidden;
    }


    /* ================================
       DETAIL CONTAINER
    ================================= */

    .detail-container {
        width: 100%;
    }


    /* ================================
       CARD
    ================================= */

    .card-box {
        width: 100%;
        box-sizing: border-box;

        background: linear-gradient(
            145deg,
            #ffffff,
            #f8fafc
        );

        border: 1px solid #eef2f7;
        border-radius: 16px;

        padding: 20px;

        box-shadow:
            0 8px 25px rgba(15, 23, 42, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);

        transition: all 0.2s ease;
    }


    /* ================================
       JUDUL SECTION
    ================================= */

    .section-title {
        display: flex;
        align-items: center;

        gap: 9px;

        margin-bottom: 16px;
        padding-bottom: 11px;

        border-bottom: 1px solid #e5e7eb;

        color: #0f172a;

        font-size: 14px;
        font-weight: 700;
    }

    .section-title::before {
        content: "";

        width: 4px;
        height: 18px;

        flex-shrink: 0;

        border-radius: 10px;

        background: linear-gradient(
            180deg,
            #6366f1,
            #3b82f6
        );
    }


    /* ================================
       ITEM
    ================================= */

    .item {
        display: grid;

        grid-template-columns: 140px minmax(0, 1fr);

        gap: 12px;

        align-items: center;

        margin-bottom: 10px;
    }

    .item:last-child {
        margin-bottom: 0;
    }


    /* ================================
       LABEL
    ================================= */

    .label {
        padding: 7px 4px;

        color: #64748b;

        font-size: 12px;
        font-weight: 600;

        line-height: 1.4;

        white-space: nowrap;
    }


    /* ================================
       VALUE
    ================================= */

    .value {
        min-width: 0;

        padding: 9px 12px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;

        border-radius: 9px;

        color: #1e293b;

        font-size: 13px;
        font-weight: 600;

        line-height: 1.4;

        overflow-wrap: anywhere;
        word-break: break-word;
    }


    /* ================================
       VALUE KOSONG
    ================================= */

    .value:empty::after {
        content: "-";
        color: #94a3b8;
        font-weight: 500;
    }


    /* ================================
       RESPONSIVE
    ================================= */

    @media (max-width: 768px) {

        .modal-dialog {
            width: calc(100% - 20px);
            max-width: none;

            margin: 10px auto;
        }

        .modal-dialog.modal-dialog-centered {
            min-height: calc(100% - 20px);
        }

        .card-box {
            padding: 17px;
            border-radius: 14px;
        }

        .section-title {
            font-size: 13px;
        }

        .item {
            grid-template-columns: 110px minmax(0, 1fr);
            gap: 8px;
        }

        .label {
            font-size: 11px;
        }

        .value {
            font-size: 12px;
            padding: 8px 10px;
        }
    }


    /* ================================
       HP SANGAT KECIL
    ================================= */

    @media (max-width: 450px) {

        .item {
            grid-template-columns: 1fr;
            gap: 3px;
            margin-bottom: 12px;
        }

        .label {
            padding: 0 3px;
        }

        .value {
            width: 100%;
        }
    }
</style>

<div class="detail-container">
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