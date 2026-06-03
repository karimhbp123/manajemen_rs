<?php
require_once('config/db.php');

$type = $_GET['type'] ?? '';

$where = "";

if ($type == 'expired') {
  $where = "DATEDIFF(masa_berlaku, CURDATE()) < 0";
} elseif ($type == 'warning') {
  $where = "DATEDIFF(masa_berlaku, CURDATE()) BETWEEN 0 AND 30";
} elseif ($type == 'aman') {
  $where = "DATEDIFF(masa_berlaku, CURDATE()) > 30";
}

$query = "
SELECT 
    nama,
    nip,
    masa_berlaku,
    jenis,
    DATEDIFF(masa_berlaku, CURDATE()) as sisa_hari
FROM (

    SELECT nama, nip, masa_berlaku, 'PNS' as jenis
    FROM pegawai_pns
    UNION ALL
    SELECT nama, nip, masa_berlaku, 'P3K Penuh Waktu' as jenis
    FROM pegawai_p3k_penuh_waktu
    UNION ALL
    SELECT nama, nip, masa_berlaku, 'P3K Paruh Waktu' as jenis
    FROM pegawai_p3k_paruh_waktu
    UNION ALL
    SELECT nama, nip, masa_berlaku, 'Kontrak' as jenis
    FROM pegawai_kontrak
    UNION ALL
    SELECT nama, nip, masa_berlaku, 'Tetap' as jenis
    FROM pegawai_tetap
    UNION ALL
    SELECT nama, '-' as nip, masa_berlaku, 'Mitra' as jenis
    FROM pegawai_mitra

) AS semua_pegawai

WHERE masa_berlaku IS NOT NULL
AND masa_berlaku != '0000-00-00'
AND $where

ORDER BY masa_berlaku ASC
";

$result = $koneksi->query($query);
?>

<style>
  .table-notif {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
  }

  /* HEADER */
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

  /* BODY */
  .table-notif td {
    padding: 12px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
  }

  /* hover row */
  .table-notif tbody tr {
    transition: all 0.2s ease;
  }

  .table-notif tbody tr:hover {
    background: #f8fafc;
  }

  /* kolom nama lebih tegas */
  .table-notif td:first-child {
    font-weight: 600;
    color: #111827;
  }

  /* tanggal lebih soft */
  .table-notif td:nth-child(3) {
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

  /* warna lebih modern */
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

  /* empty state */
  .empty-state {
    text-align: center;
    padding: 20px;
    color: #64748b;
    font-size: 13px;
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
        <th>Masa Berlaku SIP</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['nip']; ?></td>
            <td><?= date('d-m-Y', strtotime($row['masa_berlaku'])); ?></td>
            <td>
              <?php if ($row['sisa_hari'] < 0): ?>
                <span class="badge-mini badge-red">Expired</span>
              <?php elseif ($row['sisa_hari'] <= 30): ?>
                <span class="badge-mini badge-yellow">
                  <?= $row['sisa_hari']; ?> hari
                </span>
              <?php else: ?>
                <span class="badge-mini badge-green">Aktif</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="empty-state">
            Tidak ada data ditemukan
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>