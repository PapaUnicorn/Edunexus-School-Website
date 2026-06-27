<section class="section-padding" style="margin-top: 70px;">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Profil</h5>
        <h2 class="h1 fw-bold mb-4 font-heading" id="aboutTitle"><?= htmlspecialchars($page_data['about_title'] ?? 'Tentang Kami') ?></h2>
        <p class="text-secondary font-body mb-4" id="aboutDescription"><?= htmlspecialchars($page_data['about_description'] ?? '') ?></p>
        
        <div class="card border-0 shadow-sm rounded-4 p-4 mt-4 about-stats-card">
          <div class="row text-center g-4">
            <div class="col-4 border-end">
              <h3 class="fw-bold text-primary font-heading">A</h3>
              <small class="text-muted text-uppercase font-accent">Akreditasi</small>
            </div>
            <div class="col-4 border-end">
              <h3 class="fw-bold text-primary font-heading">15+</h3>
              <small class="text-muted text-uppercase font-accent">Ekskul</small>
            </div>
            <div class="col-4">
              <h3 class="fw-bold text-primary font-heading">3</h3>
              <small class="text-muted text-uppercase font-accent">Jurusan</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card border-0 shadow-lg rounded-4 p-5 h-100 bg-gradient-card text-white">
          <h3 class="fw-bold mb-4 font-heading text-white">Visi & Misi</h3>
          <div class="mb-4">
            <h5 class="text-white-50 text-uppercase fw-semibold mb-2 font-accent">Visi</h5>
            <p class="text-white fs-5 font-body" id="visionText"><?= htmlspecialchars($page_data['vision'] ?? '') ?></p>
          </div>
          <hr class="border-white-50 my-4">
          <div>
            <h5 class="text-white-50 text-uppercase fw-semibold mb-3 font-accent">Misi</h5>
            <ul class="list-unstyled text-white font-body" id="missionList">
              <?php foreach ($page_data['mission'] ?? [] as $m): ?>
                <li class="mb-2 d-flex align-items-start">
                  <i class="bi bi-check-circle-fill me-2 text-warning mt-1"></i>
                  <span><?= htmlspecialchars($m) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sejarah Sekolah Section -->
<section class="section-padding bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <h2 class="fw-bold font-heading mb-4">Sejarah Singkat</h2>
        <p class="lead text-secondary font-body mb-0" style="line-height: 1.8; text-align: justify;">
          <?= htmlspecialchars($page_data['sejarah'] ?? '') ?>
        </p>
      </div>
    </div>
  </div>
</section>
