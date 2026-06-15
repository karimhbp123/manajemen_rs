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

<?php
require_once($base_path . 'config/db.php');

if (!isset($_GET['id'])) {
  echo "ID tidak ditemukan";
  exit;
}

$id = intval($_GET['id']);

// ambil data
$query = "SELECT * FROM pegawai_p3k_paruh_waktu WHERE id = $id";
$result = $koneksi->query($query);
$data = $result->fetch_assoc();
?>

<?php require_once($base_path . 'layout/header.php'); ?>
<?php require_once($base_path . 'layout/sidebar.php'); ?>

<style>
  /* CARD UTAMA */
  .card {
    border-radius: 22px;
    padding: 35px;
    background: #ffffff;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
  }

  /* GRID UTAMA (3 KOLOM) */
  .subcard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 22px;
  }

  /* tablet */
  @media(max-width: 992px) {
    .subcard-grid {
      grid-template-columns: 1fr;
    }
  }

  /* mobile */
  @media(max-width: 576px) {
    .subcard-grid {
      grid-template-columns: 1fr;
    }
  }

  /* SUBCARD */
  .sub-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 24px;
    border: 1px solid rgba(226, 232, 240, 0.7);
    box-shadow:
      0 10px 25px rgba(0, 0, 0, 0.05),
      0 4px 10px rgba(0, 0, 0, 0.03);
    backdrop-filter: blur(6px);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  /* hover effect biar hidup */
  .sub-card:hover {
    transform: translateY(-4px);
    box-shadow:
      0 20px 40px rgba(0, 0, 0, 0.08),
      0 8px 20px rgba(0, 0, 0, 0.05);
  }

  /* SECTION TITLE PREMIUM */
  .section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 18px;
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

  /* GRID DALAM */
  .row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px 14px;
  }

  .col-md-12 {
    grid-column: span 2;
  }

  .col-md-4 {
    grid-column: span 1;
  }

  .col-md-3 {
    grid-column: span 1;
  }

  /* INPUT */
  .form-control {
    width: 100%;
    padding: 9px 14px;
    font-size: 13.5px;
    color: #0f172a;
    transition: all 0.25s ease;
    border: 1px solid transparent;
    background:
      linear-gradient(#fff, #fff) padding-box,
      linear-gradient(135deg, #6366f1, #3b82f6) border-box;
    border-radius: 14px;
  }

  /* hover halus */
  .form-control:hover {
    border-color: #cbd5f5;
    background: #fff;
  }

  /* focus premium */
  .form-control:focus {
    border-color: #6366f1;
    background: #ffffff;
    box-shadow:
      0 0 0 3px rgba(99, 102, 241, 0.15),
      0 4px 12px rgba(99, 102, 241, 0.15);
    outline: none;
  }

  textarea.form-control {
    min-height: 110px;
    resize: vertical;
  }

  label {
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    display: inline-block;
    letter-spacing: 0.2px;
  }

  textarea.form-control {
    min-height: 90px;
  }

  /* ACTION */
  .form-action {
    margin-top: 25px;
    display: flex;
    gap: 10px;
  }

  .btn {
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 14px;
  }

  .btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: #fff;
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
  }

  .btn-secondary {
    background: #f1f5f9;
    color: #1f2937;
    border: 1px solid #cbd5e1;
    transition: all 0.3s ease;
  }

  .btn-secondary:hover {
    background: #ff1100c0;
    color: #180000;
  }

  /* RESPONSIVE */
  @media(max-width: 992px) {
    .subcard-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media(max-width: 768px) {
    .subcard-grid {
      grid-template-columns: 1fr;
    }

    .row {
      grid-template-columns: 1fr;
    }
  }

  /* HEADER DASHBOARD */
  .content-header {
    padding-top: 15px;
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
</style>

<div class="content-wrapper">

  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="jenis-header">

        <div class="jenis-title-wrap">
          <div class="jenis-badge">
            <i class="fas fa-user-pen"></i>
          </div>

          <div>
            <h5>Edit Data Pegawai</h5>
            <span>Perbarui informasi pegawai</span>
          </div>
        </div>

      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <form method="POST" action="<?= $base_url ?>backend/P3K-Paruh-Waktu/update.php">
          <input type="hidden" name="id" value="<?= $data['id']; ?>">
          <div class="subcard-grid">

            <!-- 1. Data Pribadi -->
            <div class="sub-card">
              <div class="section-title">Identitas Pribadi</div>
              <div class="row">

                <div>
                  <label>Nama</label>
                  <input type="text" name="nama" class="form-control" value="<?= $data['nama']; ?>">
                </div>

                <div>
                  <label>NIK</label>
                  <input type="text" name="nik" class="form-control" value="<?= $data['nik']; ?>">
                </div>

                <div>
                  <label>Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-control">
                    <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                  </select>
                </div>

                <div>
                  <label>Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control"
                    value="<?= $data['tempat_lahir']; ?>">
                </div>

                <div>
                  <label>Agama</label>
                  <select name="agama" class="form-control">
                    <option value="">-- Pilih Agama --</option>
                    <option value="Islam" <?= $data['agama'] == 'Islam' ? 'selected' : ''; ?>>Islam</option>
                    <option value="Kristen" <?= $data['agama'] == 'Kristen' ? 'selected' : ''; ?>>Kristen</option>
                    <option value="Katolik" <?= $data['agama'] == 'Katolik' ? 'selected' : ''; ?>>Katolik</option>
                    <option value="Hindu" <?= $data['agama'] == 'Hindu' ? 'selected' : ''; ?>>Hindu</option>
                    <option value="Buddha" <?= $data['agama'] == 'Buddha' ? 'selected' : ''; ?>>Buddha</option>
                    <option value="Konghucu" <?= $data['agama'] == 'Konghucu' ? 'selected' : ''; ?>>Konghucu</option>
                  </select>
                </div>

                <div>
                  <label>Status Perkawinan</label>
                  <select name="status_perkawinan" class="form-control">
                    <option value="Belum Menikah" <?= $data['status_perkawinan'] == 'Belum Menikah' ? 'selected' : ''; ?>>Belum Menikah</option>
                    <option value="Menikah" <?= $data['status_perkawinan'] == 'Menikah' ? 'selected' : ''; ?>>Menikah</option>
                    <option value="Cerai Hidup" <?= $data['status_perkawinan'] == 'Cerai Hidup' ? 'selected' : ''; ?>>Cerai Hidup</option>
                    <option value="Cerai Mati" <?= $data['status_perkawinan'] == 'Cerai Mati' ? 'selected' : ''; ?>>Cerai Mati</option>
                  </select>
                </div>

                <div>
                  <label>Tanggal Lahir</label>
                  <input type="date" name="tanggal_lahir" class="form-control"
                    value="<?= $data['tanggal_lahir']; ?>">
                </div>

                <div class="col-md-12">
                  <label>Alamat</label>
                  <textarea name="alamat" class="form-control"><?= $data['alamat']; ?></textarea>
                </div>

                <div>
                  <label>No HP</label>
                  <input type="text" name="nomor_hp" class="form-control" value="<?= $data['nomor_hp']; ?>">
                </div>

                <div>
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>">
                </div>

              </div>
            </div>

            <!-- 2. Data Kepegawaian -->
            <div class="sub-card">
              <div class="section-title">Kepegawaian</div>
              <div class="row">

                <div>
                  <label>NIRP</label>
                  <input type="text" name="nip" class="form-control" value="<?= $data['nip']; ?>">
                </div>

                <div>
                  <label>Jabatan</label>
                  <input type="text" name="jabatan" class="form-control" value="<?= $data['jabatan']; ?>">
                </div>

                <div>
                  <label>Unit Kerja</label>
                  <input type="text" name="unit" class="form-control" value="<?= $data['unit']; ?>">
                </div>

                <div>
                  <label>Pendidikan</label>
                  <select name="pendidikan" class="form-control">
                    <option value="">-- Pilih Pendidikan --</option>
                    <option value="SLTA/SMA/SMK" <?= $data['pendidikan'] == 'SLTA/SMA/SMK' ? 'selected' : ''; ?>>SLTA/SMA/SMK</option>
                    <option value="D1" <?= $data['pendidikan'] == 'D1' ? 'selected' : ''; ?>>D1</option>
                    <option value="D2" <?= $data['pendidikan'] == 'D2' ? 'selected' : ''; ?>>D2</option>
                    <option value="D3" <?= $data['pendidikan'] == 'D3' ? 'selected' : ''; ?>>D3</option>
                    <option value="D4" <?= $data['pendidikan'] == 'D4' ? 'selected' : ''; ?>>D4</option>
                    <option value="S1" <?= $data['pendidikan'] == 'S1' ? 'selected' : ''; ?>>S1</option>
                    <option value="S2" <?= $data['pendidikan'] == 'S2' ? 'selected' : ''; ?>>S2</option>
                    <option value="S3" <?= $data['pendidikan'] == 'S3' ? 'selected' : ''; ?>>S3</option>
                  </select>
                </div>

                <div>
                  <label>Program Studi</label>
                  <input type="text" name="program_studi" class="form-control"
                    value="<?= $data['program_studi']; ?>">
                </div>

                <div>
                  <label>Status Kepegawaian</label>
                  <input type="text" class="form-control" value="PPPK PARUH WAKTU" readonly>
                  <input type="hidden" name="status_kepegawaian" value="PPPK PARUH WAKTU">
                </div>

                <div>
                  <label>TMT Kepegawaian</label>
                  <input type="date" name="tmt_kepegawaian" class="form-control"
                    value="<?= $data['tmt_kepegawaian']; ?>">
                </div>

                <div>
                  <label>TMT Masuk</label>
                  <input type="date" name="tmt_masuk" class="form-control"
                    value="<?= $data['tmt_masuk']; ?>">
                </div>

                <div>
                  <label>Masa Berlaku SIP</label>
                  <input type="date" name="masa_berlaku" class="form-control"
                    value="<?= $data['masa_berlaku']; ?>">
                </div>

                <div>
                  <label>Status Pegawai</label>
                  <select name="status_pegawai" id="statusPegawai" class="form-control">
                    <option value="AKTIF" <?= $data['status_pegawai'] == 'AKTIF' ? 'selected' : ''; ?>>AKTIF</option>
                    <option value="NONAKTIF" <?= $data['status_pegawai'] == 'NONAKTIF' ? 'selected' : ''; ?>>NONAKTIF</option>
                    <option value="PENSIUN" <?= $data['status_pegawai'] == 'PENSIUN' ? 'selected' : ''; ?>>PENSIUN</option>
                    <option value="RESIGN" <?= $data['status_pegawai'] == 'RESIGN' ? 'selected' : ''; ?>>RESIGN</option>
                  </select>
                </div>

                <div id="tmtStatusWrapper" style="display:none;">
                  <label>TMT Status Pegawai</label>
                  <input type="date" name="tmt_status" class="form-control"
                    value="<?= $data['tmt_status'] ?? ''; ?>">
                </div>

              </div>
            </div>

          </div>

          <div class="mt-4 d-flex gap-3">
            <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Update Data</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </section>
  <script>
    const statusSelect = document.getElementById('statusPegawai');
    const tmtWrapper = document.getElementById('tmtStatusWrapper');

    function toggleTMT() {
      const val = statusSelect.value;
      if (val === 'AKTIF') {
        tmtWrapper.style.display = 'none';
      } else {
        tmtWrapper.style.display = 'block';
      }
    }
    toggleTMT();
    statusSelect.addEventListener('change', toggleTMT);
  </script>
</div>
<?php require_once($base_path . 'layout/footer.php'); ?>