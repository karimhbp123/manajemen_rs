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
?>

<?php require_once($base_path . 'layout/header.php'); ?>
<?php require_once($base_path . 'layout/sidebar.php'); ?>

<style>
  /*  CARD UTAMA  */
  .card {
    border-radius: 22px;
    padding: 22px;
    background: #ffffff;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
  }

  /*  GRID UTAMA (3 KOLOM)  */
  .subcard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
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

  /*  SUBCARD  */
  .sub-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 20px;
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

  /*  SECTION TITLE PREMIUM  */
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

  /*  GRID DALAM  */
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

  /*  INPUT  */
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

  /*  ACTION  */
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

  /*  RESPONSIVE  */
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
            <i class="fas fa-user-plus"></i>
          </div>

          <div>
            <h5>Tambah Data Pegawai</h5>
            <span>Isi data pegawai baru ke dalam sistem</span>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <form method="POST" action="<?= $base_url ?>backend/P3K-Paruh-Waktu/add.php">
          <input type="hidden" name="id" value="<?= $data['id'] ?? ''; ?>">
          <div class="subcard-grid">

            <!-- 1. Data Pribadi -->
            <div class="sub-card">
              <div class="section-title">Identitas Pribadi</div>
              <div class="row">
                <div><label>Nama</label><input type="text" name="nama" class="form-control" value="" placeholder="Masukkan Nama Lengkap"></div>
                <div><label>NIK</label><input type="text" name="nik" class="form-control" value="" placeholder="Masukkan NIK"></div>
                <div><label>Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-control">
                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                </div>
                <div>
                  <label>Tempat Lahir</label>
                  <input type="text"
                    name="tempat_lahir"
                    class="form-control"
                    placeholder="Masukkan Tempat Lahir">
                </div>

                <div>
                  <label>Agama</label>
                  <select name="agama" class="form-control">
                    <option value="" selected disabled>-- Pilih Agama --</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                  </select>
                </div>

                <div>
                  <label>Status Perkawinan</label>
                  <select name="status_perkawinan" class="form-control">
                    <option value="" selected disabled>-- Pilih Status --</option>
                    <option value="Belum Menikah">Belum Menikah</option>
                    <option value="Menikah">Menikah</option>
                    <option value="Cerai Hidup">Cerai Hidup</option>
                    <option value="Cerai Mati">Cerai Mati</option>
                  </select>
                </div>
                <div><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value=""></div>
                <div class="col-md-12"><label>Alamat</label><textarea name="alamat" class="form-control" placeholder="Masukkan Alamat"></textarea></div>
                <div><label>No HP</label><input type="text" name="nomor_hp" class="form-control" value="" placeholder="Masukkan Nomor HP"></div>
                <div><label>Email</label><input type="email" name="email" class="form-control" value="" placeholder="Masukkan Email"></div>
              </div>
            </div>

            <!-- 2. Data Kepegawaian -->
            <div class="sub-card">
              <div class="section-title">Kepegawaian</div>
              <div class="row">
                <div><label>NIRP</label><input type="text" name="nip" class="form-control" value="" placeholder="Masukkan NIRP"></div>
                <div><label>Jabatan</label><input type="text" name="jabatan" class="form-control" value="" placeholder="Masukkan Jabatan"></div>
                <div><label>Unit Kerja</label><input type="text" name="unit" class="form-control" value="" placeholder="Masukkan Unit Kerja"></div>
                <div>
                  <label>Pendidikan</label>
                  <select name="pendidikan" class="form-control">
                    <option value="" selected disabled>-- Pilih Pendidikan --</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SLTA/SMA/SMK">SLTA/SMA/SMK</option>
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                  </select>
                </div>

                <div>
                  <label>Program Studi</label>
                  <input type="text"
                    name="program_studi"
                    class="form-control"
                    placeholder="Masukkan Program Studi">
                </div>
                <div>
                  <label>Status Kepegawaian</label>
                  <input type="text"
                    class="form-control"
                    value="PPPK PARUH WAKTU"
                    readonly>
                  <input type="hidden"
                    name="status_kepegawaian"
                    value="PPPK PARUH WAKTU">
                </div>
                <div><label>TMT Kepegawaian</label><input type="date" name="tmt_kepegawaian" class="form-control" value=""></div>
                <div><label>TMT Masuk</label><input type="date" name="tmt_masuk" class="form-control" value=""></div>
                <div>
                  <label>Masa Berlaku SIP</label>
                  <input type="date"
                    name="masa_berlaku"
                    class="form-control"
                    value="">
                </div>
                <div>
                  <label>Status Pegawai</label>
                  <select name="status_pegawai" id="statusPegawai" class="form-control" required>
                    <option value="" selected disabled>-- Pilih Status Pegawai --</option>
                    <option value="AKTIF">AKTIF</option>
                    <option value="NONAKTIF">NONAKTIF</option>
                    <option value="PENSIUN">PENSIUN</option>
                    <option value="RESIGN">RESIGN</option>
                  </select>
                </div>

                <!-- TMT STATUS (HIDDEN DEFAULT) -->
                <div id="tmtStatusWrapper" style="display: none;">
                  <label>TMT Status Pegawai</label>
                  <input type="date" name="tmt_status" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4 d-flex gap-3">
            <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Simpan Data</button>
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
      if (val === '' || val === 'AKTIF') {
        tmtWrapper.style.display = 'none';
      } else {
        tmtWrapper.style.display = 'block';
      }
    }
    statusSelect.addEventListener('change', toggleTMT);
  </script>
</div>
<?php require_once($base_path . 'layout/footer.php'); ?>