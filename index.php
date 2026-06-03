<?php $base_url = '/manajemen_rs/'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>👨‍💼 Aplikasi Data Pegawai </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font dulu -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600">

  <!-- Baru Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Terakhir AdminLTE -->
  <link rel="stylesheet" href="<?= $base_url ?>dist/css/adminlte.min.css">


  <style>
    body {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      font-family: 'Poppins', sans-serif;
    }

    .login-box {
      width: 360px;
    }

    .login-logo {
      color: #fff;
      font-size: 28px;
      font-weight: 600;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .login-card-body {
      border-radius: 15px;
      padding: 30px;
    }

    .form-control {
      border-radius: 10px;
    }

    .input-group-text {
      border-radius: 0 10px 10px 0;
    }

    .btn-primary {
      border-radius: 10px;
      font-weight: 600;
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .login-box-msg {
      font-size: 14px;
      color: #6b7280;
    }

    /* Tombol modern */
    .modern-btn {
      height: 45px;
      border-radius: 10px;
      font-weight: 600;
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      border: none;
      transition: 0.3s;
      font-size: 14px;
    }

    .modern-btn:hover {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      transform: translateY(-1px);
    }

    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('./assets/rs_image.jpg');
      background-size: cover;
      background-position: center;
      z-index: -3;
    }

    .bg-dark-blur {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.30);
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(3px);
      z-index: -2;
    }

    /* Icon kiri */
    .input-icon {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 13px;
    }

    /* Icon kanan */
    .input-icon-right {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #9ca3af;
      font-size: 16px;
      z-index: 10;
      padding: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Input modern (diperkecil) */
    .modern-input {
      padding-left: 36px;
      height: 40px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      font-size: 14px;
    }

    /* Placeholder */
    .modern-input::placeholder {
      font-size: 13px;
      color: #9ca3af;
    }

    /* Focus effect */
    .modern-input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    /* Jarak antar field */
    .form-group {
      margin-bottom: 14px !important;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="bg-image"></div>
  <div class="bg-dark-blur"></div>
  <div class="login-box">

    <div class="card">
      <div class="card-body login-card-body">
        <div class="text-center mb-2">
          <h3 style="font-weight:600; font-size: 24px;">Manajemen RSUD SLG</h3>
          <p class="login-box-msg">Silakan masuk untuk melanjutkan</p>
        </div>
        <!-- FORM TETAP SAMA -->
        <form action="backend/login.php" method="POST">
          
          <!-- Username -->
          <div class="form-group mb-3 position-relative">
            <i class="fa-solid fa-user input-icon"></i>
            <input type="text" name="username" class="form-control modern-input" placeholder="Username" required>
          </div>

          <!-- Password -->
          <div class="form-group mb-4 position-relative">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" class="form-control modern-input" placeholder="Password" required>
            <i class="fa-solid fa-eye input-icon-right" id="togglePassword"></i>
          </div>

          <!-- Button -->
          <button type="submit" class="btn btn-primary btn-block modern-btn">
            <i class="fa-solid fa-right-to-bracket mr-1"></i> Login
          </button>

        </form>
      </div>
    </div>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script>
    const toggle = document.getElementById('togglePassword');
    const passwordInput = document.querySelector('input[name="password"]');
    toggle.addEventListener('click', () => {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      toggle.classList.toggle('fa-eye');
      toggle.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>