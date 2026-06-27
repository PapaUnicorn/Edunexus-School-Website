<section class="section-padding" style="margin-top: 70px;">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <div class="contact-info-panel">
          <h5 class="text-primary text-uppercase fw-semibold mb-2 font-accent">Hubungi</h5>
          <h2 class="h1 fw-bold mb-4 font-heading text-dark">Hubungi Kami</h2>
          <p class="text-muted mb-4 font-body">Ada pertanyaan? Kami siap melayani dan memberikan informasi yang Anda butuhkan.</p>
          
          <ul class="list-unstyled mb-5 contact-details-list">
            <li class="d-flex align-items-start mb-4">
              <div class="contact-icon bg-primary rounded p-2 me-3 text-white">
                <i class="bi bi-geo-alt-fill fs-5"></i>
              </div>
              <div>
                <h6 class="mb-1 text-secondary font-accent">Alamat Sekolah</h6>
                <span id="contactAddress" class="font-body text-dark"><?= htmlspecialchars($page_data['address'] ?? '') ?></span>
              </div>
            </li>
            <li class="d-flex align-items-start mb-4">
              <div class="contact-icon bg-primary rounded p-2 me-3 text-white">
                <i class="bi bi-telephone-fill fs-5"></i>
              </div>
              <div>
                <h6 class="mb-1 text-secondary font-accent">Telepon</h6>
                <span id="contactPhone" class="font-body text-dark"><?= htmlspecialchars($page_data['phone'] ?? '') ?></span>
              </div>
            </li>
            <li class="d-flex align-items-start mb-4">
              <div class="contact-icon bg-primary rounded p-2 me-3 text-white">
                <i class="bi bi-envelope-fill fs-5"></i>
              </div>
              <div>
                <h6 class="mb-1 text-secondary font-accent">Surel / Email</h6>
                <span id="contactEmail" class="font-body text-dark"><?= htmlspecialchars($page_data['email'] ?? '') ?></span>
              </div>
            </li>
          </ul>

          <div class="d-flex gap-3 social-links" id="socialLinksContainer">
            <?php if (!empty($site_settings['social']['facebook'])): ?>
              <a href="<?= htmlspecialchars($site_settings['social']['facebook']) ?>" target="_blank" class="text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:38px; height:38px;"><i class="bi bi-facebook"></i></a>
            <?php endif; ?>
            <?php if (!empty($site_settings['social']['instagram'])): ?>
              <a href="<?= htmlspecialchars($site_settings['social']['instagram']) ?>" target="_blank" class="text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:38px; height:38px;"><i class="bi bi-instagram"></i></a>
            <?php endif; ?>
            <?php if (!empty($site_settings['social']['youtube'])): ?>
              <a href="<?= htmlspecialchars($site_settings['social']['youtube']) ?>" target="_blank" class="text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:38px; height:38px;"><i class="bi bi-youtube"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      
      <div class="col-lg-7">
        <div class="card border-0 rounded-4 shadow-lg overflow-hidden h-100 bg-dark-secondary">
          <div class="w-100 h-100 min-height-350 position-relative" id="mapWrapper">
            <iframe 
              src="<?= htmlspecialchars($page_data['gmaps_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.748366472092!2d106.81666675000002!3d-6.2087593!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e0c0349605%3A0xd1c1e67e9c576579!2sJakarta%20Pusat%2C%20Kota%20Jakarta%20Pusat%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid') ?>" 
              width="100%" 
              height="100%" 
              style="border:0; min-height: 400px;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              id="gmapIframe">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
