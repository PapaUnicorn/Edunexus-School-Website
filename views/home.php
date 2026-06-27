<?php
$hero = $page_data['hero_banner'] ?? [];
$kepsek = $page_data['kepala_sekolah'] ?? [];
$quick_links = $page_data['quick_links'] ?? [];
$stats = $page_data['stats'] ?? [];
$local_news = $page_data['local_news'] ?? [];

$blogger_id = $site_settings['api']['blogger_id'] ?? '';
$blogger_key = $site_settings['api']['blogger_key'] ?? '';
$is_blogger_active = (!empty($blogger_id) && $blogger_id !== 'MASUKKAN_BLOG_ID_DI_SINI' && !empty($blogger_key) && $blogger_key !== 'MASUKKAN_API_KEY_DI_SINI');
?>

<?php
$slides = $page_data['hero_slides'] ?? [];
if (empty($slides)) {
    $slides = [
        [
            'image_url' => $page_data['hero_banner']['image_url'] ?? '',
            'headline' => $page_data['hero_banner']['headline'] ?? 'Membentuk Karakter, Meraih Prestasi',
            'sub_hero' => $page_data['hero_banner']['sub_hero'] ?? '',
            'cta_text' => $page_data['hero_banner']['cta_text'] ?? 'Pelajari Selengkapnya',
            'cta_link' => $page_data['hero_banner']['cta_link'] ?? '?page=ppdb'
        ]
    ];
}
?>

<!-- Hero Slider Section -->
<div id="heroCarousel" class="carousel slide carousel-fade position-relative overflow-hidden" data-bs-ride="carousel" data-bs-interval="6000">
  <!-- Indicators -->
  <?php if (count($slides) > 1): ?>
    <div class="carousel-indicators" style="z-index: 10;">
      <?php foreach ($slides as $index => $slide): ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="carousel-inner">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
        <header class="hero-section d-flex align-items-center position-relative" style="background-image: url('<?= htmlspecialchars($slide['image_url'] ?? '') ?>'); background-size: cover; background-position: center; min-height: 80vh;">
          <div class="hero-overlay"></div>
          <div class="container position-relative z-index-2 py-5">
            <div class="row align-items-center">
              <div class="col-lg-8">
                <span class="badge bg-secondary text-uppercase mb-3 px-3 py-2 font-accent">NPSN: <?= htmlspecialchars($site_settings['npsn'] ?? '12345678') ?></span>
                <h1 class="display-3 fw-bold text-white mb-3 font-heading"><?= htmlspecialchars($slide['headline'] ?? '') ?></h1>
                <p class="lead text-white-50 mb-4 font-body"><?= htmlspecialchars($slide['sub_hero'] ?? '') ?></p>
                <div class="d-flex gap-3 flex-wrap">
                  <?php if (!empty($slide['cta_text'])): ?>
                    <a href="<?= htmlspecialchars($slide['cta_link'] ?? '?page=ppdb') ?>" class="btn btn-primary btn-lg rounded-pill px-4 py-3 font-accent"><?= htmlspecialchars($slide['cta_text']) ?></a>
                  <?php endif; ?>
                  <a href="?page=contact" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 font-accent">Hubungi Kami</a>
                </div>
              </div>
            </div>
          </div>
          <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
          </div>
        </header>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Controls -->
  <?php if (count($slides) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 10;">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 10;">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  <?php endif; ?>
</div>

<!-- Quick Links / Features -->
<section class="section-padding bg-light">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <?php foreach ($quick_links as $link): ?>
        <div class="col-md-4 col-lg-3">
          <a href="<?= htmlspecialchars($link['url']) ?>" class="text-decoration-none">
            <div class="card p-4 border-0 hover-lift shadow-sm text-center">
              <div class="card-icon mb-3 fs-2 text-primary">
                <i class="bi <?= htmlspecialchars($link['icon'] ?? 'bi-link') ?>"></i>
              </div>
              <h5 class="card-title fw-bold font-heading mb-0 text-dark"><?= htmlspecialchars($link['title']) ?></h5>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Sambutan Kepala Sekolah -->
<section class="section-padding" id="sambutanSection">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 text-center">
        <div class="principal-img-wrapper position-relative d-inline-block">
          <img src="<?= htmlspecialchars(!empty($kepsek['foto']) ? $kepsek['foto'] : 'assets/images/principal_photo.jpg') ?>" alt="Kepala Sekolah" class="img-fluid rounded shadow-lg" id="principalPhoto" style="max-height: 480px; object-fit: cover;">
          <div class="principal-accent"></div>
        </div>
      </div>
      <div class="col-lg-7">
        <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Sambutan</h5>
        <h2 class="h1 fw-bold mb-4 font-heading" id="principalWelcomeTitle"><?= htmlspecialchars($kepsek['peran'] ?? 'Kepala Sekolah') ?></h2>
        <div class="principal-message-quote font-body fs-5 mb-4 text-secondary italic">
          <p id="principalMessage">"<?= htmlspecialchars($kepsek['sambutan'] ?? '') ?>"</p>
        </div>
        <h5 class="fw-bold mb-1 font-heading" id="principalName"><?= htmlspecialchars($kepsek['nama'] ?? '') ?></h5>
        <p class="text-muted font-body mb-0"><?= htmlspecialchars($kepsek['peran'] ?? 'Kepala Sekolah') ?></p>
      </div>
    </div>
  </div>
</section>

<!-- Stats Counters -->
<section class="section-padding bg-gradient-card text-white">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-6 col-lg-3">
        <h2 class="display-4 fw-bold font-heading"><?= htmlspecialchars($stats['siswa_aktif'] ?? '0') ?></h2>
        <span class="text-white-50 text-uppercase small font-accent">Siswa Aktif</span>
      </div>
      <div class="col-6 col-lg-3">
        <h2 class="display-4 fw-bold font-heading"><?= htmlspecialchars($stats['guru_staf'] ?? '0') ?></h2>
        <span class="text-white-50 text-uppercase small font-accent">Guru & Staf</span>
      </div>
      <div class="col-6 col-lg-3">
        <h2 class="display-4 fw-bold font-heading"><?= htmlspecialchars($stats['ekstrakurikuler'] ?? '0') ?></h2>
        <span class="text-white-50 text-uppercase small font-accent">Ekstrakurikuler</span>
      </div>
      <div class="col-6 col-lg-3">
        <h2 class="display-4 fw-bold font-heading"><?= htmlspecialchars($stats['alumni'] ?? '0') ?></h2>
        <span class="text-white-50 text-uppercase small font-accent">Lulusan / Alumni</span>
      </div>
    </div>
  </div>
</section>

<!-- Berita & Pengumuman Section -->
<section class="section-padding" id="berita">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
      <div>
        <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Media</h5>
        <h2 class="h1 fw-bold mb-0 font-heading">Berita & Pengumuman</h2>
      </div>
      <div class="mt-3 mt-md-0">
        <span class="text-muted font-body" id="newsSourceLabel">
          <?php if ($is_blogger_active): ?>
            <span class="badge bg-success"><i class="bi bi-google"></i> Live Blogger API</span>
          <?php else: ?>
            <span class="badge bg-secondary"><i class="bi bi-folder2-open"></i> Lokal Pengumuman</span>
          <?php endif; ?>
        </span>
      </div>
    </div>

    <?php if ($is_blogger_active): ?>
      <div class="row g-4" id="news-grid-container">
        <!-- Skeleton Loader for Blogger API -->
        <div class="col-12 text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2 text-muted font-body">Menyinkronkan berita sekolah...</p>
        </div>
      </div>
      <!-- Inject Blogger parameters to client-side JS -->
      <script>
        window.bloggerConfig = {
          blogId: '<?= htmlspecialchars($blogger_id) ?>',
          apiKey: '<?= htmlspecialchars($blogger_key) ?>'
        };
      </script>
    <?php else: ?>
      <div class="row g-4" id="news-grid-container">
        <?php foreach ($local_news as $news): ?>
          <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden news-card hover-lift">
              <div style="height: 180px; overflow: hidden;">
                <img src="<?= htmlspecialchars($news['image'] ?? 'assets/images/placeholder.jpg') ?>" class="w-100 h-100 object-fit-cover" alt="Thumbnail">
              </div>
              <div class="card-body news-content d-flex flex-column p-3">
                <h5 class="fw-bold font-heading mb-2" style="font-size: 1.05rem;"><?= htmlspecialchars($news['title']) ?></h5>
                <p class="text-muted small font-body mb-2"><?= htmlspecialchars(date('d F Y', strtotime($news['date']))) ?></p>
                <p class="card-text text-secondary font-body small mb-3"><?= htmlspecialchars($news['summary']) ?></p>
                <button class="btn btn-outline-primary btn-sm btn-read rounded-pill mt-auto align-self-start font-accent" disabled>Pengumuman Internal</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
