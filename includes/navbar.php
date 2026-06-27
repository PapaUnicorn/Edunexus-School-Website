<?php
$currentPage = $_GET['page'] ?? 'home';
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="?page=home" id="navBrand">
      <?php if (!empty($site_settings['logo_url'])): ?>
        <img id="img-logo" src="<?= htmlspecialchars($site_settings['logo_url']) ?>" alt="Logo Institusi" height="40" class="d-inline-block align-top rounded">
      <?php else: ?>
        <img id="img-logo" src="" alt="Logo Institusi" height="40" class="d-inline-block align-top rounded" style="display:none;">
      <?php endif; ?>
      <span id="txt-nama-sekolah" class="ms-2 font-heading"><?= htmlspecialchars($site_settings['sekolah_nama'] ?? 'Sekolah') ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation" id="navToggler">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
          <li class="nav-item"><a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="?page=home" id="linkHome">Beranda</a></li>
          <li class="nav-item"><a class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>" href="?page=profile" id="linkProfil">Profil</a></li>
          <li class="nav-item"><a class="nav-link <?= $currentPage === 'academic' ? 'active' : '' ?>" href="?page=academic" id="linkAcademic">Akademik</a></li>
          <li class="nav-item"><a class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>" href="?page=contact" id="linkKontak">Kontak</a></li>
          <li class="nav-item"><a class="nav-link <?= $currentPage === 'ppdb' ? 'active' : '' ?>" href="?page=ppdb" id="linkPpdb">PPDB</a></li>
          <li class="nav-item ms-lg-3">
            <a class="btn btn-primary btn-sm rounded-pill px-3 py-2 text-white" href="dashboard/index.php" id="btnAdminDashboard">Portal Admin</a>
          </li>
        </ul>
    </div>
  </div>
</nav>
