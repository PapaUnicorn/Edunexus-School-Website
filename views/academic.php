<!-- Curriculum Section -->
<section class="section-padding" style="margin-top: 70px;" id="kurikulum">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-8">
        <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Pembelajaran</h5>
        <h2 class="h1 fw-bold mb-4 font-heading">Akademik & Kurikulum</h2>
        <p class="lead text-secondary font-body mb-4" style="line-height: 1.8; text-align: justify;">
          <?= htmlspecialchars($page_data['kurikulum_desc'] ?? '') ?>
        </p>
        <?php if (!empty($page_data['calendar_url'])): ?>
          <a href="<?= htmlspecialchars($page_data['calendar_url']) ?>" target="_blank" class="btn btn-primary btn-lg rounded-pill px-4 py-3 font-accent">
            <i class="bi bi-file-pdf-fill me-2"></i> Unduh Kalender Akademik
          </a>
        <?php endif; ?>
      </div>
      <div class="col-lg-4 text-center">
        <div class="card border-0 shadow-lg p-5 bg-gradient-card text-white text-center">
          <i class="bi bi-calendar3 fs-1 text-warning mb-3"></i>
          <h4 class="fw-bold font-heading text-white">Kalender Sekolah</h4>
          <p class="small text-white-50 font-body mb-4">Akses tanggal penting, jadwal ujian, dan libur nasional.</p>
          <?php if (!empty($page_data['calendar_url'])): ?>
            <a href="<?= htmlspecialchars($page_data['calendar_url']) ?>" target="_blank" class="btn btn-light rounded-pill px-3 py-2 text-primary font-accent small">Buka PDF</a>
          <?php else: ?>
            <button class="btn btn-light rounded-pill px-3 py-2 text-primary font-accent small" disabled>Belum Diunggah</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Program Keahlian / Jurusan Section -->
<section class="section-padding bg-light" id="jurusan">
  <div class="container">
    <div class="text-center max-w-600 mx-auto mb-5">
      <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Pilihan Karir</h5>
      <h2 class="h1 fw-bold mb-3 font-heading">Program Keahlian</h2>
      <p class="text-muted font-body">Kompetensi keahlian unggulan yang dirancang khusus mempersiapkan lulusan bersaing di dunia industri siber modern.</p>
    </div>
    
    <div class="row g-4" id="majorsContainer">
      <?php foreach ($page_data['majors'] ?? [] as $major): ?>
        <div class="col-md-4">
          <div class="card h-100 p-4 border-0 hover-lift shadow-sm">
            <div class="card-icon mb-3 fs-1 text-primary">
              <i class="bi <?= htmlspecialchars($major['icon'] ?? 'bi-mortarboard-fill') ?>"></i>
            </div>
            <h4 class="card-title fw-bold font-heading mb-3"><?= htmlspecialchars($major['name']) ?></h4>
            <p class="card-text text-secondary font-body"><?= htmlspecialchars($major['description']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Fasilitas Sekolah Section -->
<section class="section-padding" id="fasilitas">
  <div class="container">
    <div class="text-center max-w-600 mx-auto mb-5">
      <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Sarana</h5>
      <h2 class="h1 fw-bold mb-3 font-heading">Fasilitas Unggulan</h2>
      <p class="text-muted font-body">Sarana prasarana modern untuk mendukung pembelajaran praktis yang nyaman dan representatif.</p>
    </div>

    <div class="row g-4" id="facilitiesContainer">
      <?php foreach ($page_data['facilities'] ?? [] as $idx => $fac): ?>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 border-0 shadow-sm overflow-hidden news-card hover-lift">
            <div style="height: 180px; overflow: hidden;">
              <?php if (!empty($fac['image'])): ?>
                <img src="<?= htmlspecialchars($fac['image']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($fac['name']) ?>">
              <?php else: ?>
                <div class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center">
                  <i class="bi bi-building fs-1 text-muted"></i>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-body p-3 text-center">
              <h5 class="card-title fw-bold font-heading mb-0" style="font-size: 1.05rem;"><?= htmlspecialchars($fac['name']) ?></h5>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Ekstrakurikuler Section -->
<section class="section-padding bg-light" id="ekstrakurikuler">
  <div class="container">
    <div class="text-center max-w-600 mx-auto mb-5">
      <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Kreativitas</h5>
      <h2 class="h1 fw-bold mb-3 font-heading">Ekstrakurikuler</h2>
      <p class="text-muted font-body">Wadah minat, bakat, dan kreativitas siswa di luar program intrakurikuler wajib.</p>
    </div>

    <div class="row g-4 justify-content-center" id="ekskulContainer">
      <?php foreach ($page_data['extracurriculars'] ?? [] as $idx => $ekskul): ?>
        <div class="col-md-3">
          <div class="card h-100 border-0 shadow-sm overflow-hidden news-card hover-lift">
            <div style="height: 180px; overflow: hidden;">
              <?php if (!empty($ekskul['image'])): ?>
                <img src="<?= htmlspecialchars($ekskul['image']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($ekskul['name']) ?>">
              <?php else: ?>
                <div class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people-fill fs-1 text-muted"></i>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-body p-3 text-center d-flex flex-column h-100">
              <h5 class="card-title fw-bold font-heading mb-2" style="font-size: 1.05rem;"><?= htmlspecialchars($ekskul['name']) ?></h5>
              <p class="text-muted font-body small mb-0 mt-auto"><i class="bi bi-clock me-1"></i><?= htmlspecialchars($ekskul['schedule'] ?? 'Sabtu, 08:00 WIB') ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Prestasi Section -->
<section class="section-padding" id="prestasi">
  <div class="container">
    <div class="text-center max-w-600 mx-auto mb-5">
      <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Prestasi Siswa</h5>
      <h2 class="h1 fw-bold mb-3 font-heading">Torehan Kebanggaan</h2>
      <p class="text-muted font-body">Catatan medali dan pencapaian terbaik yang berhasil diraih siswa-siswi berprestasi.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php foreach ($page_data['achievements'] ?? [] as $ach): ?>
        <div class="col-md-4">
          <div class="card h-100 border-0 shadow-sm overflow-hidden news-card hover-lift">
            <div style="height: 200px; overflow: hidden;">
              <?php if (!empty($ach['image'])): ?>
                <img src="<?= htmlspecialchars($ach['image']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($ach['title']) ?>">
              <?php else: ?>
                <div class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center">
                  <i class="bi bi-trophy fs-1 text-muted"></i>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-body p-3 text-center">
              <span class="badge bg-warning text-dark mb-2 font-accent"><?= htmlspecialchars($ach['year']) ?></span>
              <h5 class="card-title fw-bold font-heading mb-0" style="font-size: 1.05rem;"><?= htmlspecialchars($ach['title']) ?></h5>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
