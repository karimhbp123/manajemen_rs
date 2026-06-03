<?php
require_once('config/db.php');

// QUERY DATA PENSIUN
$query = "
SELECT 
  nama,
  nip,
  nama_jabatan,
  tgl_lahir,

  CASE 
    WHEN LOWER(IFNULL(jabatan,'')) LIKE '%madya%' THEN 60
    ELSE 58
  END as batas_umur,

  DATE_ADD(
    tgl_lahir,
    INTERVAL 
      CASE 
        WHEN LOWER(IFNULL(jabatan,'')) LIKE '%madya%' THEN 60
        ELSE 58
      END YEAR
  ) as tgl_pensiun,

  DATEDIFF(
    DATE_ADD(
      tgl_lahir,
      INTERVAL 
        CASE 
          WHEN LOWER(IFNULL(jabatan,'')) LIKE '%madya%' THEN 60
          ELSE 58
        END YEAR
    ),
    CURDATE()
  ) as sisa_hari

FROM pegawai_pns
WHERE tgl_lahir IS NOT NULL
AND DATEDIFF(
    DATE_ADD(
      tgl_lahir,
      INTERVAL 
        CASE 
          WHEN LOWER(IFNULL(jabatan,'')) LIKE '%madya%' THEN 60
          ELSE 58
        END YEAR
    ),
    CURDATE()
) <= 365
ORDER BY tgl_pensiun ASC
LIMIT 10
";

$result = $koneksi->query($query);
?>

<style>
    /* SAMA PERSIS KAYA NOTIF SIP */
    .table-notif {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-notif thead {
        background: #f1f5f9;
    }

    .table-notif th {
        text-align: left;
        padding: 12px;
        font-weight: 600;
        color: #334155;
        font-size: 12px;
        letter-spacing: 0.3px;
    }

    .table-notif td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .table-notif tbody tr {
        transition: all 0.2s ease;
    }

    .table-notif tbody tr:hover {
        background: #f8fafc;
    }

    .table-notif td:first-child {
        font-weight: 600;
        color: #111827;
    }

    .table-notif td:nth-child(4) {
        color: #64748b;
        font-size: 12px;
    }

    /* BADGE */
    .badge-mini {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-red {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-yellow {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-green {
        background: #dcfce7;
        color: #15803d;
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #64748b;
        font-size: 13px;
    }

    /* FIX LEBAR KOLOM */
    .table-notif th:nth-child(4),
    .table-notif td:nth-child(4) {
        width: 140px;
        text-align: center;
    }

    .table-notif th:nth-child(5),
    .table-notif td:nth-child(5) {
        width: 140px;
        text-align: center;
    }

    /* BIAR GA NEMPEL */
    .table-notif td {
        white-space: nowrap;
    }

    /* KHUSUS KOLOM NAMA & JABATAN BOLEH WRAP */
    .table-notif td:nth-child(1),
    .table-notif td:nth-child(3) {
        white-space: normal;
    }

    /* BADGE LEBIH RAPI */
    .badge-mini {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 90px;
        text-align: center;
        padding: 5px 10px;
        font-size: 11px;
    }

    /* KOLOM NAMA */
    .table-notif th:nth-child(1),
    .table-notif td:nth-child(1) {
        max-width: 180px;
    }

    .table-notif td:nth-child(1) {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table-wrapper {
        max-height: 70vh;
        overflow-y: auto;
        border-radius: 12px;
        scroll-behavior: smooth;
    }

    .table-notif thead th {
        position: sticky;
        top: 0;
        background: #f1f5f9;
        z-index: 2;
    }
</style>
<div class="table-wrapper <?= ($result->num_rows == 0) ? 'no-scroll' : '' ?>">
    <table class="table-notif">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Tgl Pensiun</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td title="<?= $row['nama']; ?>">
                            <?= $row['nama']; ?>
                        </td>
                        <td><?= $row['nip']; ?></td>
                        <td><?= $row['jabatan']; ?></td>

                        <td><?= date('d-m-Y', strtotime($row['tgl_pensiun'])); ?></td>

                        <td>
                            <?php if ($row['sisa_hari'] < 0): ?>
                                <span class="badge-mini badge-red">Sudah Pensiun</span>

                            <?php elseif ($row['sisa_hari'] <= 365): ?>
                                <span class="badge-mini badge-yellow">
                                    <?= $row['sisa_hari']; ?> hari
                                </span>

                            <?php else: ?>
                                <span class="badge-mini badge-green">Masih Lama</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        Tidak ada data ditemukan
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>