<aside class="main-sidebar elevation-4 sidebar-custom">

  <a href="<?= $base_url ?>dashboard.php" class="brand-link brand-custom">
    <div class="brand-icon">
      <i class="fas fa-hospital-alt"></i>
    </div>
    <div class="brand-text">
      <strong>RSUD SLG</strong>
      <small>Sistem Manajemen</small>
    </div>
  </a>

  <div class="sidebar">
    <nav>
      <ul class="nav nav-pills nav-sidebar flex-column">

        <?php $current_url = $_SERVER['REQUEST_URI']; ?>

        <li class="nav-item">
          <a href="<?= $base_url ?>dashboard.php"
             class="nav-link <?= (strpos($current_url, '/dashboard.php') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">DATA PEGAWAI</li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/PNS"
             class="nav-link <?= (strpos($current_url, '/pages/PNS') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>PNS</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/P3K-Penuh-Waktu"
             class="nav-link <?= (strpos($current_url, '/pages/P3K-Penuh-Waktu') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-check"></i>
            <p>PPPK Penuh</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/P3K-Paruh-Waktu"
             class="nav-link <?= (strpos($current_url, '/pages/P3K-Paruh-Waktu') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-clock"></i>
            <p>PPPK Paruh</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/Tetap"
             class="nav-link <?= (strpos($current_url, '/pages/Tetap') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Tetap</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/Kontrak"
             class="nav-link <?= (strpos($current_url, '/pages/Kontrak') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-edit"></i>
            <p>Kontrak</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/Mitra"
             class="nav-link <?= (strpos($current_url, '/pages/Mitra') !== false) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Mitra</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>