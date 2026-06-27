  <!-- Footer -->
  <footer class="footer py-4 bg-dark text-white-50 border-top border-secondary">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0 font-body">&copy; <span id="footerYear"><?= date('Y') ?></span> <span id="footerSchoolName" class="text-white fw-bold"><?= htmlspecialchars($site_settings['sekolah_nama'] ?? 'Sekolah') ?></span>. Semua Hak Dilindungi.</p>
          <small class="text-muted">NPSN: <?= htmlspecialchars($site_settings['npsn'] ?? '12345678') ?></small>
        </div>
        <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
          <ul class="list-inline mb-0 font-accent">
            <li class="list-inline-item"><a href="?page=home" class="text-white-50 text-decoration-none">Beranda</a></li>
            <li class="list-inline-item ms-3"><a href="?page=profile" class="text-white-50 text-decoration-none">Profil</a></li>
            <li class="list-inline-item ms-3"><a href="dashboard/index.php" class="text-white-50 text-decoration-none">Dashboard Admin</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Compiled Main Javascript Bundle from Vite -->
  <script src="assets/js/main.js"></script>
</body>
</html>
