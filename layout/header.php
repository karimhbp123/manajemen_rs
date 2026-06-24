  <?php $base_url = '/manajemen_rs/'; ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen RS SLG</title> <!-- Font -->
    <!-- Font dulu -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600">
    <!-- Baru Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Terakhir AdminLTE -->
    <link rel="stylesheet" href="<?= $base_url ?>dist/css/adminlte.min.css">

    <style>
      body {
        font-family: 'Poppins', sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }

      .nav-sidebar .nav-header {
        font-weight: 600;
        font-size: 11px;
        color: #94a3b8;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin: 18px 16px 8px;
        padding: 0;
      }

      .nav-item {
        font-weight: 600;
      }

      .nav-sidebar .nav-link p {
        margin: 0;
        line-height: 1.2;
        font-size: 13px;
      }

      .btn-logout {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #dc3545;
        color: #fff;
        padding: 7px 14px;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        margin-right: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.25);
      }

      /* hover tetap warna sama */
      .btn-logout:hover {
        color: #fff;
        /* 🔥 ini kuncinya */
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(220, 53, 69, 0.35);
      }

      /* active */
      .btn-logout:active {
        color: #fff;
        transform: translateY(0) scale(0.97);
        box-shadow: 0 3px 8px rgba(220, 53, 69, 0.2);
      }

      /* icon */
      .btn-logout i {
        font-size: 14px;
      }

      .btn-menu {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        color: #1e293b;
        background: #f1f5f9;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        transform: translateX(8px);
      }

      /* hover tetap bawa translateX */
      .btn-menu:hover {
        transform: translateX(8px) translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        outline: 1px solid rgba(0, 0, 0, 0.05);
        /* subtle highlight */
        z-index: 5;
      }

      /* icon */
      .btn-menu i {
        font-size: 18px;
      }

      .form-control-sidebar {
        background-color: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
      }

      .form-control-sidebar::placeholder {
        color: #cbd5f5;
      }

      .btn-sidebar {
        background-color: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
      }

      .btn-sidebar:hover {
        background-color: rgba(255, 255, 255, 0.2);
      }

      /* SIDEBAR */
      .sidebar-custom {
        background: #0f172a !important;
      }

      .brand-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 8px 22px;
        color: #fff !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.08);
        text-align: center;
      }

      .brand-icon {
        font-size: 20px;
        color: #38bdf8;
      }

      .brand-text {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        line-height: 1.2;
        font-weight: 800;
      }

      .brand-text small {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 400;
        margin-top: 2px;
        letter-spacing: 0.3px;
      }

      .sidebar {
        padding-top: 10px;
      }

      .nav-sidebar .nav-link {
        color: #e2e8f0;
        border-radius: 10px;
      }

      .nav-sidebar .nav-icon {
        font-size: 15px;
        color: #94a3b8;
      }

      .nav-sidebar .nav-link {
        transition: all 0.2s ease;
      }

      .nav-sidebar .nav-link:hover {
        background: #1e293b;
        color: #fff;
      }

      .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      }

      .nav-sidebar .nav-link.active .nav-icon,
      .nav-sidebar .nav-link:hover .nav-icon {
        color: #f5f5f3;
      }

      /* HILANG TOTAL SAAT COLLAPSE */
      .sidebar-collapse .main-sidebar {
        margin-left: -250px !important;
      }

      /* CONTENT FULL */
      .sidebar-collapse .content-wrapper,
      .sidebar-collapse .main-footer,
      .sidebar-collapse .main-header {
        margin-left: 0 !important;
      }

      .main-sidebar {
        transition: margin-left 0.3s ease;
        width: 250px;
      }

      .content-wrapper,
      .main-header,
      .main-footer {
        transition: margin-left 0.3s ease;
      }

      .sidebar-search {
        position: relative;
      }

      .sidebar-search input {
        width: 100%;
        height: 42px;
        border: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, .08);
        color: #fff;
        padding: 0 45px 0 15px;
        transition: .25s;
        font-size: 13px;
      }

      .sidebar-search input::placeholder {
        color: rgba(255, 255, 255, .6);
      }

      .sidebar-search input:focus {
        outline: none;
        background: rgba(255, 255, 255, .12);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, .3);
      }

      .sidebar-search button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: #6366f1;
        color: #fff;
        cursor: pointer;
        transition: .2s;
      }

      .sidebar-search button:hover {
        background: #4f46e5;
      }
    </style>
  </head>

  <body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper"> <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link btn-menu" data-widget="pushmenu" href="#">
              <i class="fa-solid fa-bars-staggered"></i>
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item d-flex align-items-center">
            <a href="<?= $base_url ?>backend/logout.php"
              class="btn-logout"
              onclick="return confirm('Yakin mau logout?')">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </nav>