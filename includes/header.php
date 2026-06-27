<?php
$themeMap = [
  'tema-nova' => 'classic',
  'tema-siber' => 'modern',
  'tema-lestari' => 'eco',
  'tema-minimalis' => 'minimalist',
  'tema-ceria' => 'playful'
];
$themeClass = $themeMap[$site_settings['tema_aktif'] ?? 'tema-nova'] ?? 'classic';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title id="meta-title"><?= htmlspecialchars($site_settings['sekolah_nama'] ?? 'Sekolah') ?></title>
  <?php if (!empty($site_settings['logo_url'])): ?>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($site_settings['logo_url']) ?>">
  <?php endif; ?>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Fredoka:wght@400;600&family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
  
  <!-- Bootstrap Icons via CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Compiled CSS from Vite -->
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- Dynamic Brand Color Overwrite -->
  <style id="brandColorOverride">
    :root {
      --warna-utama: <?= htmlspecialchars($site_settings['warna_brand'] ?? '#0056b3') ?>;
    }
    body {
      --primary-color: var(--warna-utama) !important;
    }
    .btn-primary, .btn-login {
      background-color: var(--warna-utama) !important;
      border-color: var(--warna-utama) !important;
      color: #FFF !important;
    }
    .btn-primary:hover, .btn-login:hover {
      filter: brightness(0.95) !important;
      color: #FFF !important;
    }
    .text-primary, .navbar .nav-link.active, .navbar .nav-link:hover, .navbar-brand {
      color: var(--warna-utama) !important;
    }
    .bg-gradient-card {
      background: linear-gradient(135deg, var(--warna-utama) 0%, #0d131f 100%) !important;
    }
    .contact-icon {
      background-color: var(--warna-utama) !important;
    }
    .theme-playful .btn-primary {
      box-shadow: 0 6px 0 rgba(0,0,0,0.2) !important;
    }
  </style>
</head>
<body class="theme-<?= $themeClass ?>">
