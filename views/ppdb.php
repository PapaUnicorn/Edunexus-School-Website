<section class="section-padding" style="margin-top: 70px;">
  <div class="container">
    <div class="row g-5 align-items-center mb-5">
      <div class="col-lg-7">
        <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Penerimaan Siswa Baru</h5>
        <h1 class="display-4 fw-bold font-heading mb-4 text-dark"><?= htmlspecialchars($page_data['headline'] ?? 'Informasi PPDB') ?></h1>
        <p class="lead text-secondary font-body mb-4" style="line-height: 1.8;"><?= htmlspecialchars($page_data['deskripsi'] ?? '') ?></p>
        <a href="?page=contact" class="btn btn-primary btn-lg rounded-pill px-4 py-3 font-accent">Hubungi Panitia PPDB</a>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-lg bg-gradient-card text-white p-5">
          <h3 class="fw-bold font-heading mb-4 text-white">Syarat Pendaftaran</h3>
          <ul class="list-unstyled font-body mb-0">
            <?php foreach ($page_data['persyaratan'] ?? [] as $req): ?>
              <li class="mb-3 d-flex align-items-start">
                <i class="bi bi-patch-check-fill me-2 text-warning mt-1" style="font-size: 1.1rem;"></i>
                <span><?= htmlspecialchars($req) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Alur Pendaftaran -->
    <div class="pt-5 border-top">
      <div class="text-center max-w-600 mx-auto mb-5">
        <h2 class="fw-bold font-heading mb-3 text-dark">Alur Pendaftaran</h2>
        <p class="text-muted font-body">Ikuti 4 langkah mudah pendaftaran siber PPDB online.</p>
      </div>

      <div class="row g-4 justify-content-center">
        <?php foreach ($page_data['alur_pendaftaran'] ?? [] as $alur): ?>
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4 border-0 shadow-sm text-center position-relative hover-lift">
              <div class="position-absolute top-0 start-50 translate-middle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold font-heading" style="width: 40px; height: 40px; border: 4px solid #fff; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <?= htmlspecialchars($alur['step']) ?>
              </div>
              <div class="pt-3">
                <h5 class="card-title fw-bold font-heading mb-2 text-dark"><?= htmlspecialchars($alur['title']) ?></h5>
                <p class="card-text text-secondary font-body small mb-0"><?= htmlspecialchars($alur['description']) ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
