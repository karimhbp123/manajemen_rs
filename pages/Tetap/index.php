<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: index.php");
  exit;
}

// base
$base_url  = '/manajemen_rs/';
$base_path = $_SERVER['DOCUMENT_ROOT'] . $base_url;
?>

<?php require_once($base_path . 'layout/header.php'); ?>
<?php require_once($base_path . 'layout/sidebar.php'); ?>
<?php require_once($base_path . 'config/db.php'); ?>

<style>
  /* GLOBAL LAYOUT */
  html,
  body {
    height: 100%;
    overflow: hidden;
  }

  .content-wrapper {
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .content {
    flex: 1;
    overflow: hidden;
  }

  .content-header {
    padding-top: 10px;
    padding-bottom: 10px;
  }

  /* HEADER DASHBOARD */
  .jenis-header {
    display: flex;
    align-items: center;
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

  /* PAGE TITLE */
  .page-title-modern {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .title-main {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .title-main h4 {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    letter-spacing: 0.4px;
  }

  .title-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    box-shadow: 0 0 10px rgba(79, 70, 229, 0.5);
  }

  .page-title-modern p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    max-width: 480px;
    line-height: 1.4;
  }

  /* BUTTON */
  .action-buttons-modern {
    display: flex;
    gap: 10px;
  }

  .btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
  }

  .btn-modern i {
    font-size: 12px;
  }

  .btn-modern.primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff;
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.25);
  }

  .btn-modern.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(79, 70, 229, 0.35);
  }

  .btn-modern.success {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.25);
  }

  .btn-modern.success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(16, 185, 129, 0.35);
  }

  .btn-modern:active {
    transform: scale(0.96);
  }

  /* CARD */
  .card {
    border-radius: 16px;
    background: #ffffff;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
  }

  .card-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
  }

  /* TABLE */
  .table {
    width: 100%;
    min-width: 900px;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: auto;
    font-family: 'Segoe UI', sans-serif;
    border-radius: 10px;
    overflow: visible;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  }

  .table-responsive {
    overflow-y: auto;
    flex: 1;
    max-height: calc(100vh - 300px);
    scroll-behavior: smooth;
  }

  .table th,
  .table td {
    vertical-align: middle !important;
    white-space: nowrap;
  }

  /* SCROLLBAR */
  .table-responsive::-webkit-scrollbar {
    width: 6px;
  }

  .table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }

  .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  /* HEADER */
  .table thead th {
    background: linear-gradient(135deg, #1e40af, #1e3a8a);
    color: #f8fafc;
    text-align: center;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    border-right: 1px solid rgba(255, 255, 255, 0.15);
    position: sticky;
    top: 0;
    z-index: 999;
    font-size: 12px;
    padding: 14px;
  }

  .table thead th:last-child {
    border-right: none;
  }

  /* BODY */
  .table td {
    color: #334155;
    border-bottom: 1px solid #e5e7eb;
    border-right: 1px solid #f1f5f9;
    position: relative;
    z-index: 1;
    font-size: 12.5px;
    padding: 10px 12px;
  }

  .table td:last-child {
    border-right: none;
  }

  .table td a {
    position: relative;
    z-index: 2;
  }

  /* COLUMN SPECIAL */
  .table td:nth-child(2) {
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
  }

  .table td:nth-child(6),
  .table td:nth-child(7),
  .table td:nth-child(9) {
    text-align: center;
  }

  /* ZEBRA */
  .table tbody tr:nth-child(even) {
    background-color: #f9fafb;
  }

  /* HOVER */
  .table-hover tbody tr:hover {
    box-shadow: inset 0 0 0 9999px rgba(0, 0, 0, 0.02);
    background-color: #eef2ff;
    transform: scale(1.002);
  }

  .table-hover tbody tr {
    transition: all 0.2s ease;
  }

  /* LEFT BORDER EFFECT */
  .table tbody tr td:first-child {
    border-left: 3px solid transparent;
  }

  .table tbody tr:hover td:first-child {
    border-left: 3px solid #3b82f6;
  }

  /* BUTTON IN TABLE */
  .table .btn {
    border-radius: 5px;
    padding: 3px 6px;
    font-size: 11px;
  }

  .table .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  }

  .table td:last-child .btn {
    width: 22px;
    height: 22px;
    padding: 2px 5px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .table td:last-child .btn-warning:hover {
    background: #f59e0b;
  }

  .table td:last-child .btn-danger:hover {
    background: #ef4444;
  }

  /* SEARCH */
  .table-toolbar {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
    flex-wrap: wrap;
    background: #ffffff;
    padding: 12px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .search-modern {
    position: relative;
    width: 340px;
    flex: 1;
    max-width: 300px;
  }

  .search-modern input {
    width: 100%;
    height: 42px;
    padding: 0 42px 0 40px;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    background: linear-gradient(145deg, #f9fafb, #ffffff);
    font-size: 13px;
    transition: all 0.25s ease;
  }

  .search-modern i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #9ca3af;
    transition: 0.2s;
  }

  .clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #9ca3af;
    cursor: pointer;
    display: none;
    transition: 0.2s;
  }

  .search-modern:hover input {
    border-color: #c7d2fe;
  }

  .search-modern input:focus {
    outline: none;
    border-color: #6366f1;
    background: #ffffff;
    box-shadow:
      0 0 0 3px rgba(99, 102, 241, 0.15),
      0 6px 20px rgba(99, 102, 241, 0.15);
  }

  .search-modern input:focus+i,
  .search-modern:focus-within i {
    color: #6366f1;
  }

  .clear-btn:hover {
    color: #ef4444;
  }

  /* ALERT */
  .custom-alert {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    min-width: 280px;
    max-width: 350px;
    padding: 10px 16px;
    border-radius: 12px;
    color: #fff;
    z-index: 9999;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    animation: slideFade 0.5s ease;
  }

  .success-alert {
    background: linear-gradient(135deg, #22c55e, #16a34a);
  }

  .alert-content {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .alert-icon {
    font-size: 20px;
  }

  .alert-text {
    flex: 1;
    font-weight: 500;
  }

  .alert-close {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    opacity: 0.7;
  }

  .alert-close:hover {
    opacity: 1;
  }

  .custom-alert .progress-timer {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 0%;
    background: rgba(255, 255, 255, 0.6);
  }

  @keyframes slideFade {
    from {
      transform: translate(-50%, -25px) scale(0.95);
      opacity: 0;
    }

    to {
      transform: translate(-50%, 0) scale(1);
      opacity: 1;
    }
  }


  /* MODAL CONTAINER */
  .modal-dialog {
    max-width: 850px;
    margin-top: 60px;
  }

  .modal-content {
    display: flex;
    flex-direction: column;
    max-height: 85vh;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
  }

  /* HEADER PREMIUM */
  .modal-header.custom-header {
    position: sticky;
    top: 0;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    box-shadow:
      0 6px 20px rgba(79, 70, 229, 0.35),
      inset 0 -1px 0 rgba(255, 255, 255, 0.15);
    border-bottom: none;
    overflow: hidden;
  }

  /* efek cahaya (biar gak flat) */
  .modal-header.custom-header::before {
    content: "";
    position: absolute;
    top: -40%;
    left: -20%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at center,
        rgba(255, 255, 255, 0.25),
        transparent 60%);
    opacity: 0.3;
    pointer-events: none;
  }

  /* garis bawah glowing tipis */
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

  /* TITLE */
  .modal-title {
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: 0.4px;
  }

  /* icon jadi lebih hidup */
  .modal-title i {
    font-size: 16px;
    background: rgba(255, 255, 255, 0.2);
    padding: 6px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  }

  /* CLOSE BUTTON */
  .close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #fff;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
  }

  .close-btn:hover {
    background: #ef4444;
    transform: rotate(90deg) scale(1.05);
    box-shadow: 0 6px 15px rgba(239, 68, 68, 0.4);
  }

  /* BODY (SCROLL AREA) */
  .modal-body {
    overflow-y: auto;
    padding: 18px;
    background: #f9fafb;
  }

  /* scrollbar modern */
  .modal-body::-webkit-scrollbar {
    width: 6px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }


  /* ACTION BUTTON (TABLE) */
  .action-group {
    display: flex;
    justify-content: center;
    gap: 8px;
  }

  .action-btn {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 1px solid transparent;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
  }

  .action-btn i {
    transition: 0.2s;
  }

  .action-btn.view {
    color: #3b82f6;
    border-color: #e0e7ff;
  }

  .action-btn.view:hover {
    background: #3b82f6;
    color: #fff;
    transform: translateY(-2px);
  }

  .action-btn.edit {
    color: #f59e0b;
    border-color: #fef3c7;
  }

  .action-btn.edit:hover {
    background: #f59e0b;
    color: #fff;
    transform: translateY(-2px);
  }

  .action-btn.delete {
    color: #ef4444;
    border-color: #fee2e2;
  }

  .action-btn.delete:hover {
    background: #ef4444;
    color: #fff;
    transform: translateY(-2px);
  }

  .action-btn::after {
    content: attr(title);
    position: absolute;
    top: 50%;
    right: 38px;
    transform: translateY(-50%) translateX(5px);
    font-size: 11px;
    background: #111827;
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    opacity: 0;
    transition: 0.2s;
    pointer-events: none;
    white-space: nowrap;
  }

  .action-btn:hover::after {
    opacity: 1;
    transform: translateY(-50%) translateX(0);
  }

  .action-btn:active::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.1);
    top: 0;
    left: 0;
  }

  /* FILTER STATUS */
  .filter-modern {
    height: 42px;
    padding: 0 12px;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    background: linear-gradient(145deg, #f9fafb, #ffffff);
    font-size: 13px;
    transition: all 0.25s ease;
    cursor: pointer;
  }

  /* hover */
  .filter-modern:hover {
    border-color: #c7d2fe;
  }

  /* focus */
  .filter-modern:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow:
      0 0 0 3px rgba(99, 102, 241, 0.15),
      0 6px 20px rgba(99, 102, 241, 0.15);
  }

  .badge-status {
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 10px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    letter-spacing: 0.3px;
    line-height: 1;
    transition: all 0.2s ease;
  }

  /* AKTIF */
  .badge-status.AKTIF {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    box-shadow: 0 3px 8px rgba(34, 197, 94, 0.25);
  }

  /* NONAKTIF */
  .badge-status.NONAKTIF {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 3px 8px rgba(239, 68, 68, 0.25);
  }

  /* RESIGN */
  .badge-status.RESIGN {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    box-shadow: 0 3px 8px rgba(245, 158, 11, 0.25);
  }

  /* PENSIUN */
  .badge-status.PENSIUN {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
    box-shadow: 0 3px 8px rgba(99, 102, 241, 0.25);
  }

  /* hover halus */
  .badge-status:hover {
    filter: brightness(1.05);
    transform: scale(1.05);
  }

  /* NO */
  .col-no {
    width: 50px;
    min-width: 50px;
    text-align: center;
  }

  /* NAMA (boleh full) */
  .col-nama {
    width: 220px;
    min-width: 180px;
    white-space: nowrap;
    overflow: visible;
  }

  /* JABATAN (dipotong) */
  .col-jabatan {
    width: 160px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* UNIT (dipotong) */
  .col-unit {
    width: 140px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* ALAMAT (dipotong) */
  .col-alamat {
    width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* TMT (full) */
  .col-tmt {
    width: 120px;
    white-space: nowrap;
    text-align: center;
  }

  /* MASA KERJA (full) */
  .col-masa {
    width: 140px;
    white-space: nowrap;
    text-align: center;
  }

  /* NO HP (full) */
  .col-hp {
    width: 140px;
    white-space: nowrap;
  }

  /* EMAIL (full tapi tetap rapi) */
  .col-email {
    min-width: 220px;
    white-space: normal;
    word-break: break-word;
  }

  /* STATUS */
  .col-status {
    width: 100px;
    text-align: center;
  }

  /* AKSI */
  .col-aksi {
    width: 100px;
    text-align: center;
  }

  .col-nama,
  .col-jabatan,
  .col-alamat {
    position: relative;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="jenis-header">
        <div class="jenis-title-wrap">
          <div class="jenis-badge">
            <i class="fas fa-id-card"></i>
          </div>
          <div>
            <h5>Manajemen Pegawai</h5>
            <span>Kelola data pegawai tetap secara lengkap</span>
          </div>
        </div>
      </div>
      <div class="action-buttons-modern">
        <a href="addTetap.php" class="btn-modern primary">
          <i class="fas fa-plus"></i>
          <span>Tambah</span>
        </a>
        <a href="../../export_pegawai.php?jenis=tetap"
          class="btn-modern success"
          target="_blank"
          rel="noopener noreferrer">
          <i class="fas fa-download"></i>
          <span>Export</span>
        </a>
      </div>

    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card shadow border-0">
        <div class="card-body">
          <div class="table-toolbar">
            <div class="search-modern">
              <i class="fas fa-search"></i>
              <input type="text" id="searchInput" placeholder="Cari nama pegawai...">
              <span class="clear-btn" id="clearSearch">&times;</span>
            </div>
            <select id="filterStatus" class="filter-modern">
              <option value="AKTIF">AKTIF</option>
              <option value="NONAKTIF">NONAKTIF</option>
              <option value="PENSIUN">PENSIUN</option>
              <option value="RESIGN">RESIGN</option>
            </select>
          </div>
          <?php if (isset($_GET['msg'])): ?>
            <div class="custom-alert success-alert">
              <div class="alert-content">
                <div class="alert-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-text">
                  <?php
                  if ($_GET['msg'] == 'add_success') {
                    echo "Data berhasil ditambahkan!";
                  } elseif ($_GET['msg'] == 'update_success') {
                    echo "Data berhasil diupdate!";
                  } elseif ($_GET['msg'] == 'delete_success') {
                    echo "Data berhasil dihapus!";
                  }
                  ?>
                </div>
                <button class="alert-close">&times;</button>
              </div>
              <div class="progress-timer"></div>
            </div>
          <?php endif; ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle text-sm">
              <thead id="tableHead">
                <tr class="thead-full">
                  <th>NO</th>
                  <th>NAMA</th>
                  <th>JENIS KELAMIN</th>
                  <th>JABATAN</th>
                  <th>UNIT KERJA</th>
                  <th>TMT</th>
                  <th>MASA KERJA</th>
                  <th>ALAMAT</th>
                  <th>NO HP</th>
                  <th>EMAIL</th>
                  <th>STATUS</th>
                  <th>AKSI</th>
                </tr>
                <tr class="thead-simple" style="display:none;">
                  <th width="10">NO</th>
                  <th width="100">NAMA</th>
                  <th width="80">JABATAN</th>
                  <th width="80">UNIT KERJA</th>
                  <th width="30">STATUS</th>
                  <th width="30">TMT STATUS</th>
                  <th width="60">AKSI</th>
                </tr>
              </thead>
              <?php
              $query = "SELECT * FROM pegawai_tetap";
              $result = $koneksi->query($query);
              $no = 1;
              ?>
              <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="data-row">
                      <td class="col-no text-center"><?= $no++; ?></td>
                      <td class="col-nama"><?= $row['nama']; ?></td>
                      <td class="col-jk"><?= $row['jenis_kelamin']; ?></td>
                      <td class="col-jabatan"><?= $row['jabatan']; ?></td>
                      <td class="col-unit"><?= $row['unit']; ?></td>
                      <td class="col-tmt"><?= date('d-m-Y', strtotime($row['tmt_masuk'])); ?></td>
                      <td class="col-masa">
                        <?php
                        $tmt = new DateTime($row['tmt_masuk']); // tanggal mulai kerja
                        $now = new DateTime(); // tanggal sekarang
                        $diff = $now->diff($tmt);
                        echo $diff->y . " Tahun " . $diff->m . " Bulan";
                        ?>
                      </td>
                      <td title="<?= $row['alamat']; ?>" class="col-alamat"><?= $row['alamat']; ?></td>
                      <td class="col-hp"><?= $row['nomor_hp']; ?></td>
                      <td class="col-email"><?= $row['email']; ?></td>
                      <td class="col-status text-center">
                        <?php
                        $status = strtoupper(trim($row['status_pegawai']));
                        $map = [
                          'AKTIF' => 'AKTIF',
                          'NONAKTIF' => 'NONAKTIF',
                          'RESIGN' => 'RESIGN',
                          'PENSIUN' => 'PENSIUN'
                        ];
                        $textMap = [
                          'AKTIF' => 'AKTIF',
                          'NONAKTIF' => 'NONAKTIF',
                          'RESIGN' => 'RESIGN',
                          'PENSIUN' => 'PENSIUN'
                        ];
                        $class = $map[$status] ?? 'AKTIF';
                        $text  = $textMap[$status] ?? 'Aktif';
                        ?>
                        <span class="badge-status <?= $class; ?>"
                          data-status="<?= $status; ?>"
                          title="<?= $status; ?>">
                          <?php if ($class == 'AKTIF'): ?>
                          <?php elseif ($class == 'NONAKTIF'): ?>
                          <?php elseif ($class == 'RESIGN'): ?>
                          <?php elseif ($class == 'PENSIUN'): ?>
                          <?php endif; ?>
                          <?= $text; ?>
                        </span>
                      </td>
                      <td class="col-tmt-status">
                        <?php
                        if (!empty($row['tmt_status'])) {
                          echo date('d-m-Y', strtotime($row['tmt_status']));
                        } else {
                          echo '-';
                        }
                        ?>
                      </td>
                      <td class="col-aksi text-center">
                        <div class="action-group">
                          <button
                            class="action-btn view"
                            onclick="showDetail(<?= $row['id']; ?>, '<?= $row['nama']; ?>')"
                            title="Detail">
                            <i class="fas fa-eye"></i>
                          </button>
                          <a href="editTetap.php?id=<?= $row['id']; ?>"
                            class="action-btn edit"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="../../backend/Tetap/delete.php?id=<?= $row['id']; ?>"
                            class="action-btn delete"
                            onclick="return confirm('Yakin hapus data?')"
                            title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="11" class="text-center text-muted">Data tidak ada</td>
                  </tr>
                <?php endif; ?>
                <tr id="noDataRow" style="display:none;">
                  <td colspan="12" class="text-center text-muted">
                    Tidak ada data ditemukan
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>
  <script>
    const alertBox = document.querySelector('.custom-alert');
    if (alertBox) {
      const timer = alertBox.querySelector('.progress-timer');
      const closeBtn = alertBox.querySelector('.alert-close');
      let width = 0;
      const duration = 2000;
      const intervalTime = 10;
      const step = (intervalTime / duration) * 100;
      const interval = setInterval(() => {
        width += step;
        timer.style.width = width + '%';
        if (width >= 100) {
          closeAlert();
        }
      }, intervalTime);

      function closeAlert() {
        clearInterval(interval);
        alertBox.style.transition = 'all 0.4s ease';
        alertBox.style.opacity = '0';
        alertBox.style.transform = 'translateY(-10px)';
        setTimeout(() => alertBox.remove(), 400);
      }
      closeBtn.addEventListener('click', closeAlert);
      if (window.history.replaceState) {
        const cleanUrl = window.location.href.split('?')[0];
        window.history.replaceState(null, null, cleanUrl);
      }
    }

    function showDetail(id, nama) {
      document.getElementById('modalNama').innerHTML =
        '<i class="fas fa-id-card"></i> ' + nama;
      fetch('detailTetap.php?id=' + id)
        .then(res => res.text())
        .then(data => {
          document.getElementById('detailContent').innerHTML = data;
          $('#detailModal').modal('show');
        });
    }

    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    searchInput.addEventListener('keyup', function() {
      const keyword = this.value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');
      clearBtn.style.display = keyword ? 'block' : 'none';
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(keyword)) {
          row.style.display = '';
          row.style.backgroundColor = keyword ? '#eef2ff' : '';
        } else {
          row.style.display = 'none';
        }
      });
    });
    clearBtn.addEventListener('click', () => {
      searchInput.value = '';
      searchInput.dispatchEvent(new Event('keyup'));
      clearBtn.style.display = 'none';
    });

    //filter status
    const filterStatus = document.getElementById('filterStatus');

    function filterTable() {
      const keyword = searchInput.value.toLowerCase();
      const statusFilter = filterStatus.value.toUpperCase();
      const rows = document.querySelectorAll('tbody tr.data-row');
      let visibleCount = 0;
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const status = row.querySelector('.badge-status')?.dataset.status;
        const matchSearch = text.includes(keyword);
        const matchStatus = !statusFilter || status === statusFilter;
        if (matchSearch && matchStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });
      const noDataRow = document.getElementById('noDataRow');
      if (visibleCount === 0) {
        noDataRow.style.display = '';
      } else {
        noDataRow.style.display = 'none';
      }
      resetNumber();
    }

    filterStatus.addEventListener('change', () => {
      localStorage.setItem('statusFilter', filterStatus.value);
      filterTable();
      switchTableMode();
    });

    searchInput.addEventListener('keyup', () => {
      filterTable();
    });

    // set default filter ke AKTIF saat pertama load
    document.addEventListener('DOMContentLoaded', () => {
      const savedStatus = localStorage.getItem('statusFilter');
      filterStatus.value = savedStatus ? savedStatus : 'AKTIF';
      filterTable();
      switchTableMode();
      resetNumber();
    });

    function switchTableMode() {
      const status = filterStatus.value.toUpperCase();
      const wrapper = document.querySelector('.table-responsive');
      const theadFull = document.querySelector('.thead-full');
      const theadSimple = document.querySelector('.thead-simple');
      const rows = document.querySelectorAll('.data-row');
      if (status === 'AKTIF' || status === '') {
        theadFull.style.display = '';
        theadSimple.style.display = 'none';
        rows.forEach(row => {
          row.querySelectorAll('.col-jk, .col-tmt, .col-masa, .col-alamat, .col-hp, .col-email')
            .forEach(td => td.style.display = '');
          row.querySelectorAll('.col-tmt-status')
            .forEach(td => td.style.display = 'none');
        });
      } else {
        theadFull.style.display = 'none';
        theadSimple.style.display = '';
        rows.forEach(row => {
          row.querySelectorAll('.col-jk, .col-tmt, .col-masa, .col-alamat, .col-hp, .col-email')
            .forEach(td => td.style.display = 'none');
          row.querySelectorAll('.col-tmt-status')
            .forEach(td => td.style.display = '');
        });
      }
      if (status === 'AKTIF' || status === '') {
        wrapper.classList.remove('thead-simple-active');
      } else {
        wrapper.classList.add('thead-simple-active');
      }
    }

    function resetNumber() {
      const rows = document.querySelectorAll('tbody tr');
      let no = 1;
      rows.forEach(row => {
        if (row.style.display !== 'none') {
          const cell = row.querySelector('.col-no');
          if (cell) {
            cell.innerText = no++;
          }
        }
      });
    }
    window.addEventListener('beforeunload', () => {
      localStorage.removeItem('statusFilter');
    });
  </script>
</div>
<div class="modal fade" id="detailModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header custom-header">
        <h5 class="modal-title" id="modalNama">
          <i class="fas fa-id-card"></i> Detail Pegawai
        </h5>
        <button type="button" class="close-btn" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="detailContent">
        Loading...
      </div>
    </div>
  </div>
</div>

<?php require_once($base_path . 'layout/footer.php'); ?>