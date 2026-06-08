<style>
.footer-modern {
  font-size: 13px;
  padding: 16px 28px;
  background: linear-gradient(145deg, #ffffff, #f1f5f9);
  border-top: 1px solid rgba(226, 232, 240, 0.8);
  color: #64748b;
  position: relative;
  backdrop-filter: blur(10px);
  box-shadow: 0 -8px 30px rgba(0, 0, 0, 0.08);
  z-index: 10;
}

/* garis atas elegan */
.footer-modern::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 2px;
  width: 100%;
  opacity: 0.8;
}

/* container */
.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

/* kiri */
.footer-left strong {
  color: #0f172a;
  font-weight: 700;
}

.footer-left span {
  margin-left: 6px;
  font-size: 12px;
  color: #94a3b8;
}

/* kanan */
.footer-right {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12.5px;
}

.footer-right i {
  color: #4f46e5;
  font-size: 14px;
}

/* divider kecil */
.footer-divider {
  width: 1px;
  height: 14px;
  background: #cbd5e1;
  margin: 0 8px;
}

/* responsive */
@media (max-width: 576px) {
  .footer-content {
    flex-direction: column;
    text-align: center;
    gap: 6px;
  }

  .footer-divider {
    display: none;
  }
}
</style>

<footer class="main-footer footer-modern">
  <div class="footer-content">

    <div class="footer-left">
      © 2026 <strong>Manajemen RSUD SLG</strong>
      <span>| Sistem Informasi Kepegawaian</span>
    </div>

    <div class="footer-right">
      <i class="fa-solid fa-hospital"></i>
      <span>RSUD SLG</span>

      <div class="footer-divider"></div>

      <span>v1.0</span>
    </div>

  </div>
</footer>
<!-- CSS langsung -->
<script src="<?= $base_url ?>plugins/jquery/jquery.min.js"></script>
<script src="<?= $base_url ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base_url ?>dist/js/adminlte.min.js"></script>
</body>

</html>