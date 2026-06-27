<?php
session_start();
require_once 'credentials.php';

// Strict Authentication Check
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit;
}

// Inactivity check
$inactive = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}
$_SESSION['last_activity'] = time();

// Read config files
$site_settings = json_decode(file_get_contents('../config/site_settings.json'), true) ?? [];
$home_data = json_decode(file_get_contents('../config/home.json'), true) ?? [];
$profile_data = json_decode(file_get_contents('../config/profile.json'), true) ?? [];
$academic_data = json_decode(file_get_contents('../config/academic.json'), true) ?? [];
$ppdb_data = json_decode(file_get_contents('../config/ppdb.json'), true) ?? [];
$contact_data = json_decode(file_get_contents('../config/contact.json'), true) ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS Administrator | <?= htmlspecialchars($site_settings['sekolah_nama'] ?? 'Sekolah') ?></title>
  
  <!-- Bootstrap 5 CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Quill WYSIWYG Editor Snow CSS -->
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

  <style>
    :root {
      --bg-dark: #0f172a;
      --bg-sidebar: #1e293b;
      --bg-main: #f8fafc;
      --border-color: #cbd5e1;
      --text-dark: #1e293b;
      --text-muted: #64748b;
      --primary: #3b82f6;
      --primary-hover: #2563eb;
      --success: #10b981;
    }

    body {
      background-color: var(--bg-main);
      color: var(--text-dark);
      font-family: 'Outfit', sans-serif;
      height: 100vh;
      overflow: hidden;
      margin: 0;
    }

    .app-container {
      display: flex;
      height: 100vh;
      width: 100vw;
    }

    /* sidebar style */
    .sidebar {
      width: 280px;
      background-color: var(--bg-sidebar);
      color: white;
      display: flex;
      flex-direction: column;
      height: 100%;
      flex-shrink: 0;
      box-shadow: 4px 0 15px rgba(0,0,0,0.05);
    }
    .sidebar-header {
      padding: 24px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .sidebar-logo {
      background: linear-gradient(135deg, var(--primary) 0%, #a855f7 100%);
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }
    .sidebar-menu {
      flex-grow: 1;
      padding: 20px 12px;
      overflow-y: auto;
    }
    .menu-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      color: rgba(255,255,255,0.7);
      text-decoration: none;
      border-radius: 12px;
      font-weight: 500;
      margin-bottom: 6px;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .menu-link:hover {
      background-color: rgba(255,255,255,0.05);
      color: white;
    }
    .menu-link.active {
      background-color: var(--primary);
      color: white;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
    .sidebar-footer {
      padding: 20px 24px;
      border-top: 1px solid rgba(255,255,255,0.08);
    }

    /* main content style */
    .main-content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      height: 100%;
      overflow: hidden;
    }
    .top-navbar {
      background-color: white;
      border-bottom: 1px solid var(--border-color);
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }
    #settingsMasterForm {
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      height: 100%;
      overflow: hidden;
    }
    .workspace-pane {
      flex-grow: 1;
      overflow-y: auto;
      padding: 30px;
    }

    .form-section-card {
      background-color: white;
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.01);
    }
    .section-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #e2e8f0;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-label {
      color: var(--text-muted);
      font-weight: 500;
      font-size: 0.85rem;
      margin-bottom: 6px;
    }
    .form-control, .form-select {
      border: 1px solid var(--border-color);
      border-radius: 10px;
      padding: 10px 14px;
      color: var(--text-dark);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* upload styles */
    .upload-box {
      border: 2px dashed var(--border-color);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      background-color: #f8fafc;
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
    }
    .upload-box:hover {
      border-color: var(--primary);
      background-color: rgba(59, 130, 246, 0.02);
    }
    .upload-icon {
      font-size: 1.8rem;
      color: var(--text-muted);
      margin-bottom: 6px;
    }
    .upload-preview-wrapper {
      max-width: 120px;
      margin: 10px auto 0 auto;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid var(--border-color);
    }
    .upload-preview {
      width: 100%;
      height: auto;
      display: block;
    }

    /* list items */
    .item-card {
      background-color: #f8fafc;
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 12px;
      position: relative;
    }

    /* Floating Save Button / Header Button */
    .btn-save-all {
      background-color: var(--success);
      color: white;
      font-weight: 600;
      border: none;
      padding: 10px 24px;
      border-radius: 30px;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
      transition: all 0.2s ease;
    }
    .btn-save-all:hover {
      background-color: #059669;
      transform: translateY(-1px);
      color: white;
    }

    /* Quill custom height */
    .editor-container {
      height: 180px;
      background-color: white;
      border-radius: 0 0 10px 10px;
    }
    .ql-toolbar {
      background-color: #f1f5f9;
      border-color: var(--border-color) !important;
      border-radius: 10px 10px 0 0;
    }
    .ql-container {
      border-color: var(--border-color) !important;
    }

    /* Toast notification */
    .toast-panel {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: white;
      border: 1px solid var(--border-color);
      border-left: 5px solid var(--primary);
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      border-radius: 8px;
      padding: 15px 20px;
      z-index: 10000;
      display: flex;
      align-items: center;
      gap: 12px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(100px);
      transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease, visibility 0.3s ease;
    }
    .toast-panel.toast-show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .color-picker-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .color-indicator {
      width: 44px;
      height: 40px;
      border-radius: 8px;
      border: 1px solid var(--border-color);
      padding: 0;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="app-container">
    
    <!-- Sidebar Menu Left -->
    <div class="sidebar">
      <div class="sidebar-header">
        <div class="sidebar-logo">
          <i class="bi bi-shield-check text-white fs-4"></i>
        </div>
        <div>
          <h6 class="mb-0 fw-bold">Edunexus CMS</h6>
          <small class="text-white-50" style="font-size: 0.7rem; letter-spacing: 0.5px;">SYSTEM PANEL</small>
        </div>
      </div>
      
      <div class="sidebar-menu">
        <div class="menu-link active" id="menu-global" onclick="switchTab('global')">
          <i class="bi bi-globe"></i>
          <span>Pengaturan Global</span>
        </div>
        <div class="menu-link" id="menu-home" onclick="switchTab('home')">
          <i class="bi bi-house-door-fill"></i>
          <span>Konten Beranda</span>
        </div>
        <div class="menu-link" id="menu-profile" onclick="switchTab('profile')">
          <i class="bi bi-info-circle-fill"></i>
          <span>Konten Profil</span>
        </div>
        <div class="menu-link" id="menu-academic" onclick="switchTab('academic')">
          <i class="bi bi-book-half"></i>
          <span>Konten Akademik</span>
        </div>
        <div class="menu-link" id="menu-ppdb" onclick="switchTab('ppdb')">
          <i class="bi bi-file-earmark-person"></i>
          <span>Konten PPDB</span>
        </div>
        <div class="menu-link" id="menu-keamanan" onclick="switchTab('keamanan')">
          <i class="bi bi-key-fill"></i>
          <span>Keamanan Portal</span>
        </div>
      </div>

      <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
          <i class="bi bi-person-fill text-white-50"></i>
          <span class="small text-white-50">admin</span>
        </div>
        <a href="logout.php" class="text-danger small text-decoration-none d-block"><i class="bi bi-box-arrow-left"></i> Keluar Sesi</a>
      </div>
    </div>

    <!-- Main Form Editor Area Right -->
    <div class="main-content">
      
      <!-- Top Navbar Header -->
      <div class="top-navbar">
        <div>
          <h5 class="mb-0 fw-bold" id="panel-title-text">Pengaturan Global</h5>
          <small class="text-muted">Konfigurasi branding, kontak, media sosial, dan integrasi API</small>
        </div>
        <div>
          <button type="button" class="btn-save-all" id="btnSaveAll">
            <i class="bi bi-floppy-fill"></i>
            <span>Simpan Perubahan (Save All to JSON)</span>
          </button>
        </div>
      </div>

      <!-- Main Config Form -->
      <form id="settingsMasterForm" enctype="multipart/form-data">
        <div class="workspace-pane">
          
          <!-- TAB 1: PENGATURAN GLOBAL (`tab-global`) -->
          <div class="tab-content-panel" id="tab-global">
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-palette-fill text-primary"></i> Identitas & Desain Website</h6>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="sekolah_nama" class="form-label">Nama Sekolah</label>
                  <input type="text" class="form-control" name="sekolah_nama" id="sekolah_nama" value="<?= htmlspecialchars($site_settings['sekolah_nama'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                  <label for="npsn" class="form-label">NPSN Sekolah</label>
                  <input type="text" class="form-control" name="npsn" id="npsn" value="<?= htmlspecialchars($site_settings['npsn'] ?? ''); ?>">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="warna_brand" class="form-label">Warna Brand Utama (Primary Color)</label>
                  <div class="color-picker-wrapper color-picker-group">
                    <input type="color" class="color-indicator" name="warna_brand" id="warna_brand" value="<?= htmlspecialchars($site_settings['warna_brand'] ?? '#0056b3'); ?>">
                    <span class="text-dark small" id="colorTextHex"><?= htmlspecialchars($site_settings['warna_brand'] ?? '#0056b3'); ?></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="tema_aktif" class="form-label">Desain / Tema Website</label>
                  <select class="form-select" name="tema_aktif" id="tema_aktif">
                    <option value="tema-nova" <?= ($site_settings['tema_aktif'] ?? 'tema-nova') == 'tema-nova' ? 'selected' : ''; ?>>Classic Academy (Nova Theme)</option>
                    <option value="tema-siber" <?= ($site_settings['tema_aktif'] ?? '') == 'tema-siber' ? 'selected' : ''; ?>>Modern Tech (Siber Theme)</option>
                    <option value="tema-lestari" <?= ($site_settings['tema_aktif'] ?? '') == 'tema-lestari' ? 'selected' : ''; ?>>Eco Green (Lestari Theme)</option>
                    <option value="tema-minimalis" <?= ($site_settings['tema_aktif'] ?? '') == 'tema-minimalis' ? 'selected' : ''; ?>>Minimalist Corporate</option>
                    <option value="tema-ceria" <?= ($site_settings['tema_aktif'] ?? '') == 'tema-ceria' ? 'selected' : ''; ?>>Playful Elementary (Ceria Theme)</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="status_maintenance" class="form-label">Status Pemeliharaan (Maintenance Mode)</label>
                  <select class="form-select" name="status_maintenance" id="status_maintenance">
                    <option value="false" <?= ($site_settings['status_maintenance'] ?? 'false') == 'false' ? 'selected' : ''; ?>>Nonaktif (Website Online)</option>
                    <option value="true" <?= ($site_settings['status_maintenance'] ?? 'false') == 'true' ? 'selected' : ''; ?>>Aktif (Website Offline / Maintenance)</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Logo Sekolah (Favicon otomatis sinkron)</label>
                <div class="upload-box" onclick="document.getElementById('logoInput').click()">
                  <i class="bi bi-cloud-arrow-up upload-icon text-info"></i>
                  <p class="mb-0 text-muted small">Pilih file logo (Format: PNG/JPG/SVG/WEBP, Maks. 2MB)</p>
                  <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*">
                  <div class="upload-preview-wrapper" id="logoPreviewWrapper" style="<?= empty($site_settings['logo_url']) ? 'display:none;' : ''; ?>">
                    <img src="../<?= htmlspecialchars($site_settings['logo_url'] ?? ''); ?>" class="upload-preview" id="logoPreview">
                  </div>
                </div>
              </div>
            </div>

            <!-- GLOBAL CONTACT INFO -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-telephone-fill text-success"></i> Informasi Hubung & Peta</h6>
              <div class="mb-3">
                <label for="contact_address" class="form-label">Alamat Lengkap Kantor</label>
                <textarea class="form-control" name="contact_address" id="contact_address" rows="3"><?= htmlspecialchars($contact_data['address'] ?? ''); ?></textarea>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="contact_phone" class="form-label">Nomor Telepon</label>
                  <input type="text" class="form-control" name="contact_phone" id="contact_phone" value="<?= htmlspecialchars($contact_data['phone'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="contact_email" class="form-label">Surel / Email Resmi</label>
                  <input type="email" class="form-control" name="contact_email" id="contact_email" value="<?= htmlspecialchars($contact_data['email'] ?? ''); ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="contact_map" class="form-label">Google Maps Embed Link (Iframe Src URL)</label>
                <input type="url" class="form-control" name="contact_map" id="contact_map" value="<?= htmlspecialchars($contact_data['gmaps_url'] ?? ''); ?>">
              </div>
            </div>

            <!-- GLOBAL SOCIAL MEDIA -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-share-fill text-primary"></i> Media Sosial Resmi</h6>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Instagram Link</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-instagram text-danger"></i></span>
                    <input type="url" class="form-control" name="social_instagram" value="<?= htmlspecialchars($site_settings['social']['instagram'] ?? ''); ?>" placeholder="https://instagram.com/sekolah">
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Facebook Link</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-facebook text-primary"></i></span>
                    <input type="url" class="form-control" name="social_facebook" value="<?= htmlspecialchars($site_settings['social']['facebook'] ?? ''); ?>" placeholder="https://facebook.com/sekolah">
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">TikTok Link</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-tiktok text-dark"></i></span>
                    <input type="url" class="form-control" name="social_tiktok" value="<?= htmlspecialchars($site_settings['social']['tiktok'] ?? ''); ?>" placeholder="https://tiktok.com/@sekolah">
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">YouTube Link</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-youtube text-danger"></i></span>
                    <input type="url" class="form-control" name="social_youtube" value="<?= htmlspecialchars($site_settings['social']['youtube'] ?? ''); ?>" placeholder="https://youtube.com/sekolah">
                  </div>
                </div>
              </div>
            </div>

            <!-- BLOGGER API INTEGRATION -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-google text-success"></i> Google Blogger API Integration</h6>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="blogger_blog_id" class="form-label">Blogger Blog ID</label>
                  <input type="text" class="form-control" name="blogger_blog_id" id="blogger_blog_id" value="<?= htmlspecialchars($site_settings['api']['blogger_id'] ?? ''); ?>" placeholder="Blog ID">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="blogger_api_key" class="form-label">Blogger API Key</label>
                  <input type="password" class="form-control" name="blogger_api_key" id="blogger_api_key" value="<?= htmlspecialchars($site_settings['api']['blogger_key'] ?? ''); ?>" placeholder="API Key">
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 2: BERANDA CONTENT (`tab-home`) -->
          <div class="tab-content-panel d-none" id="tab-home">
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-images text-warning"></i> Slide Banner Utama (Maksimal 5)</h6>
              <div id="hero-slides-list">
                <?php 
                $slides = $home_data['hero_slides'] ?? [];
                if (empty($slides) && !empty($home_data['hero_banner'])) {
                    $slides = [$home_data['hero_banner']];
                }
                foreach ($slides as $idx => $slide): 
                ?>
                  <div class="item-card slide-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-secondary">Slide #<span class="item-number"><?= $idx + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3">
                      <div class="col-md-4 mb-3">
                        <label class="form-label small">Foto Latar Slide</label>
                        <input type="file" class="form-control form-control-sm" name="hero_slide_image_<?= $idx ?>" accept="image/*">
                        <?php if (!empty($slide['image_url'])): ?>
                          <div class="mt-2 small text-muted text-truncate"><i class="bi bi-file-earmark-image"></i> <?= basename($slide['image_url']) ?></div>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-8">
                        <div class="mb-2">
                          <label class="form-label small">Headline Utama</label>
                          <input type="text" class="form-control form-control-sm" name="hero_slide_headline[]" value="<?= htmlspecialchars($slide['headline'] ?? '') ?>" required>
                        </div>
                        <div class="mb-2">
                          <label class="form-label small">Sub Headline</label>
                          <input type="text" class="form-control form-control-sm" name="hero_slide_sub[]" value="<?= htmlspecialchars($slide['sub_hero'] ?? '') ?>">
                        </div>
                        <div class="row g-2">
                          <div class="col-6">
                            <label class="form-label small">Teks CTA</label>
                            <input type="text" class="form-control form-control-sm" name="hero_slide_cta_text[]" value="<?= htmlspecialchars($slide['cta_text'] ?? '') ?>">
                          </div>
                          <div class="col-6">
                            <label class="form-label small">Tautan CTA</label>
                            <input type="text" class="form-control form-control-sm" name="hero_slide_cta_link[]" value="<?= htmlspecialchars($slide['cta_link'] ?? '') ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-warning btn-sm rounded-pill mt-2" id="btn-add-slide"><i class="bi bi-plus-circle"></i> Tambah Slide</button>
            </div>

            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-person-badge text-primary"></i> Profil Kepala Sekolah</h6>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Foto Kepala Sekolah</label>
                  <div class="upload-box" onclick="document.getElementById('kepsekPhotoInput').click()">
                    <i class="bi bi-person-fill-add upload-icon"></i>
                    <p class="mb-0 text-muted small">Pilih foto formal kepsek</p>
                    <input type="file" name="foto_kepsek" id="kepsekPhotoInput" class="d-none" accept="image/*">
                    <div class="upload-preview-wrapper" id="kepsekPhotoPreviewWrapper" style="<?= empty($home_data['kepala_sekolah']['foto']) ? 'display:none;' : ''; ?>">
                      <img src="../<?= htmlspecialchars($home_data['kepala_sekolah']['foto'] ?? '') ?>" class="upload-preview" id="kepsekPhotoPreview">
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-3">
                    <label for="kepsek_nama" class="form-label">Nama Lengkap & Gelar</label>
                    <input type="text" class="form-control" name="kepsek_nama" id="kepsek_nama" value="<?= htmlspecialchars($home_data['kepala_sekolah']['nama'] ?? '') ?>">
                  </div>
                  <div class="mb-3">
                    <label for="kepsek_jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" name="kepsek_jabatan" id="kepsek_jabatan" value="<?= htmlspecialchars($home_data['kepala_sekolah']['peran'] ?? 'Kepala Sekolah') ?>">
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="kepsek_sambutan" class="form-label">Naskah Sambutan Kepala Sekolah</label>
                <textarea class="form-control" name="kepsek_sambutan" id="kepsek_sambutan" rows="4"><?= htmlspecialchars($home_data['kepala_sekolah']['sambutan'] ?? '') ?></textarea>
              </div>
            </div>

            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-bar-chart text-success"></i> Data Statistik Sekolah</h6>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label for="stat_siswa" class="form-label">Siswa Aktif</label>
                  <input type="number" class="form-control" name="stat_siswa" id="stat_siswa" value="<?= htmlspecialchars($home_data['stats']['siswa_aktif'] ?? '0') ?>">
                </div>
                <div class="col-md-3 mb-3">
                  <label for="stat_guru" class="form-label">Guru & Staf</label>
                  <input type="number" class="form-control" name="stat_guru" id="stat_guru" value="<?= htmlspecialchars($home_data['stats']['guru_staf'] ?? '0') ?>">
                </div>
                <div class="col-md-3 mb-3">
                  <label for="stat_ekskul" class="form-label">Ekstrakurikuler</label>
                  <input type="number" class="form-control" name="stat_ekskul" id="stat_ekskul" value="<?= htmlspecialchars($home_data['stats']['ekstrakurikuler'] ?? '0') ?>">
                </div>
                <div class="col-md-3 mb-3">
                  <label for="stat_alumni" class="form-label">Alumni</label>
                  <input type="number" class="form-control" name="stat_alumni" id="stat_alumni" value="<?= htmlspecialchars($home_data['stats']['alumni'] ?? '0') ?>">
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 3: PROFIL CONTENT (`tab-profile`) -->
          <div class="tab-content-panel d-none" id="tab-profile">
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-compass text-primary"></i> Identitas Visi & Misi</h6>
              <div class="mb-4">
                <label class="form-label">Visi Sekolah (Rich Text Editor)</label>
                <div id="quillVisi" class="editor-container"></div>
                <input type="hidden" name="visi" id="visiVal">
              </div>
              <div>
                <label class="form-label">Misi Sekolah (Rich Text Editor)</label>
                <div id="quillMisi" class="editor-container"></div>
                <input type="hidden" name="misi" id="misiVal">
              </div>
            </div>

            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-clock-history text-warning"></i> Sejarah Sekolah</h6>
              <div class="mb-3">
                <label class="form-label">Gambar Sejarah</label>
                <div class="upload-box" onclick="document.getElementById('historyImgInput').click()">
                  <i class="bi bi-image-fill upload-icon"></i>
                  <p class="mb-0 text-muted small">Pilih file gambar sejarah sekolah</p>
                  <input type="file" name="gambar_sejarah" id="historyImgInput" class="d-none" accept="image/*">
                  <div class="upload-preview-wrapper" id="historyImgPreviewWrapper" style="<?= empty($profile_data['history_image_url']) ? 'display:none;' : ''; ?>">
                    <img src="../<?= htmlspecialchars($profile_data['history_image_url'] ?? '') ?>" class="upload-preview" id="historyImgPreview">
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="teks_sejarah" class="form-label">Naskah Sejarah Sekolah</label>
                <textarea class="form-control" name="teks_sejarah" id="teks_sejarah" rows="5"><?= htmlspecialchars($profile_data['sejarah'] ?? '') ?></textarea>
              </div>
            </div>

            <!-- DYNAMIC TEACHER DIRECTORY -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-person-video3 text-success"></i> Direktori Guru & Tenaga Kependidikan (Maksimal 10)</h6>
              <div id="guru_list">
                <?php 
                $teachers = $profile_data['teachers'] ?? [];
                foreach ($teachers as $idx => $t): 
                ?>
                  <div class="item-card guru-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-secondary">Guru #<span class="item-number"><?= $idx + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-md-3">
                        <label class="form-label small">Foto Guru</label>
                        <input type="file" class="form-control form-control-sm" name="teacher_photo_<?= $idx ?>">
                        <?php if (!empty($t['photo'])): ?>
                          <div class="mt-2 small text-muted text-truncate"><i class="bi bi-file-earmark-image"></i> <?= basename($t['photo']) ?></div>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-5">
                        <label class="form-label small">Nama Lengkap & Gelar</label>
                        <input type="text" class="form-control form-control-sm" name="teacher_name[]" value="<?= htmlspecialchars($t['name'] ?? '') ?>" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label small">Mata Pelajaran / Jabatan</label>
                        <input type="text" class="form-control form-control-sm" name="teacher_role[]" value="<?= htmlspecialchars($t['role'] ?? '') ?>">
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mt-2" id="btn-add-guru"><i class="bi bi-plus-circle"></i> Tambah Guru</button>
            </div>
          </div>

          <!-- TAB 4: KONTEN AKADEMIK & KESISWAAN (`tab-academic`) -->
          <div class="tab-content-panel d-none" id="tab-academic">
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-journal-text text-primary"></i> Deskripsi Kurikulum & Kalender</h6>
              <div class="mb-3">
                <label for="deskripsi_kurikulum" class="form-label">Penjelasan Kurikulum Sekolah</label>
                <textarea class="form-control" name="deskripsi_kurikulum" id="deskripsi_kurikulum" rows="4"><?= htmlspecialchars($academic_data['kurikulum_desc'] ?? '') ?></textarea>
              </div>
              <div class="mb-3">
                <label for="calendarInput" class="form-label">Unggah Kalender Akademik (.PDF)</label>
                <input type="file" class="form-control" name="kalender_akademik" id="calendarInput" accept=".pdf">
                <?php if (!empty($academic_data['calendar_url'])): ?>
                  <div class="mt-2 small text-muted"><i class="bi bi-file-pdf-fill text-danger"></i> <a href="../<?= htmlspecialchars($academic_data['calendar_url']) ?>" target="_blank"><?= basename($academic_data['calendar_url']) ?></a></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- DYNAMIC MAJORS (JURUSAN) -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-mortarboard text-danger"></i> Program Keahlian (Maksimal 10)</h6>
              <div id="majors-wrapper">
                <?php 
                $majors = $academic_data['majors'] ?? [];
                foreach ($majors as $index => $major): 
                ?>
                  <div class="card p-3 mb-3 major-item" style="background-color:rgba(0,0,0,0.02); border:1px solid var(--border-color); border-radius:10px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="badge bg-secondary">Jurusan #<span class="item-number"><?= $index + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Nama Program</label>
                      <input type="text" class="form-control form-control-sm" name="major_name[]" value="<?= htmlspecialchars($major['name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Deskripsi Singkat</label>
                      <textarea class="form-control form-control-sm" name="major_desc[]" rows="2"><?= htmlspecialchars($major['description'] ?? ''); ?></textarea>
                    </div>
                    <div>
                      <label class="form-label">Ikon Class (Bootstrap Icons)</label>
                      <input type="text" class="form-control form-control-sm" name="major_icon[]" value="<?= htmlspecialchars($major['icon'] ?? 'bi-mortarboard-fill'); ?>" placeholder="Contoh: bi-code-slash">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-danger btn-sm rounded-pill mt-2" id="btn-add-major"><i class="bi bi-plus-circle"></i> Tambah Jurusan</button>
            </div>

            <!-- DYNAMIC FACILITIES (SARANA) -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-building text-info"></i> Sarana & Fasilitas Sekolah (Maksimal 10)</h6>
              <div id="fasilitas_list">
                <?php 
                $facilities = $academic_data['facilities'] ?? [];
                foreach ($facilities as $idx => $f): 
                ?>
                  <div class="item-card facility-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-secondary">Fasilitas #<span class="item-number"><?= $idx + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-md-4">
                        <label class="form-label small">Gambar Fasilitas</label>
                        <input type="file" class="form-control form-control-sm" name="facility_photo_<?= $idx ?>">
                        <?php if (!empty($f['image'])): ?>
                          <div class="mt-2 small text-muted text-truncate"><i class="bi bi-file-earmark-image"></i> <?= basename($f['image']) ?></div>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-8">
                        <label class="form-label small">Caption / Nama Fasilitas</label>
                        <input type="text" class="form-control form-control-sm" name="facility_name[]" value="<?= htmlspecialchars($f['name'] ?? '') ?>" required>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-info btn-sm rounded-pill mt-2" id="btn-add-facility"><i class="bi bi-plus-circle"></i> Tambah Fasilitas</button>
            </div>

            <!-- DYNAMIC EXTRACURRICULARS -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-palette text-success"></i> Kegiatan Ekstrakurikuler (Maksimal 10)</h6>
              <div id="ekskul_list">
                <?php 
                $ekskuls = $academic_data['extracurriculars'] ?? [];
                foreach ($ekskuls as $idx => $e): 
                ?>
                  <div class="item-card ekskul-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-secondary">Ekskul #<span class="item-number"><?= $idx + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-md-3">
                        <label class="form-label small">Ikon / Gambar</label>
                        <input type="file" class="form-control form-control-sm" name="ekskul_icon_<?= $idx ?>">
                        <?php if (!empty($e['image'])): ?>
                          <div class="mt-2 small text-muted text-truncate"><i class="bi bi-file-earmark-image"></i> <?= basename($e['image']) ?></div>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-5">
                        <label class="form-label small">Nama Ekstrakurikuler</label>
                        <input type="text" class="form-control form-control-sm" name="ekskul_name[]" value="<?= htmlspecialchars($e['name'] ?? '') ?>" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label small">Jadwal Latihan</label>
                        <input type="text" class="form-control form-control-sm" name="ekskul_schedule[]" value="<?= htmlspecialchars($e['schedule'] ?? 'Sabtu, 08:00 WIB') ?>">
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-success btn-sm rounded-pill mt-2" id="btn-add-ekskul"><i class="bi bi-plus-circle"></i> Tambah Ekskul</button>
            </div>

            <!-- DYNAMIC ACHIEVEMENTS -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-trophy text-warning"></i> Prestasi Sekolah (Maksimal 10)</h6>
              <div id="prestasi_list">
                <?php 
                $achievements = $academic_data['achievements'] ?? [];
                foreach ($achievements as $idx => $a): 
                ?>
                  <div class="item-card prestasi-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-secondary">Prestasi #<span class="item-number"><?= $idx + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-md-3">
                        <label class="form-label small">Bukti / Foto</label>
                        <input type="file" class="form-control form-control-sm" name="achievement_photo_<?= $idx ?>">
                        <?php if (!empty($a['image'])): ?>
                          <div class="mt-2 small text-muted text-truncate"><i class="bi bi-file-earmark-image"></i> <?= basename($a['image']) ?></div>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-5">
                        <label class="form-label small">Judul Penghargaan / Prestasi</label>
                        <input type="text" class="form-control form-control-sm" name="achievement_title[]" value="<?= htmlspecialchars($a['title'] ?? '') ?>" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label small">Tahun Penghargaan</label>
                        <input type="number" class="form-control form-control-sm" name="achievement_year[]" value="<?= htmlspecialchars($a['year'] ?? '2026') ?>">
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-warning btn-sm rounded-pill mt-2" id="btn-add-prestasi"><i class="bi bi-plus-circle"></i> Tambah Prestasi</button>
            </div>
          </div>

          <!-- TAB 5: PPDB CONFIG (`tab-ppdb`) -->
          <div class="tab-content-panel d-none" id="tab-ppdb">
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-toggle-on text-primary"></i> Status Pendaftaran & Tautan PPDB</h6>
              <div class="mb-3 d-flex align-items-center justify-content-between">
                <div>
                  <label class="form-label mb-0 d-block fw-bold text-dark">Status PPDB Online</label>
                  <small class="text-muted">Buka atau tutup akses pendaftaran siswa baru secara global</small>
                </div>
                <div class="form-check form-switch fs-4">
                  <input class="form-check-input" type="checkbox" role="switch" name="ppdb_status" id="ppdbStatusSwitch" <?= ($ppdb_data['status_pendaftaran'] ?? 'tutup') == 'buka' ? 'checked' : ''; ?>>
                </div>
              </div>
              <div class="mb-3">
                <label for="ppdb_cta_link" class="form-label">Tautan Pendaftaran (Google Form / WhatsApp)</label>
                <input type="url" class="form-control" name="ppdb_cta_link" id="ppdb_cta_link" value="<?= htmlspecialchars($ppdb_data['cta_link'] ?? '') ?>" placeholder="https://forms.gle/abc123xyz">
              </div>
            </div>

            <!-- DYNAMIC ALUR PENDAFTARAN -->
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-bezier2 text-warning"></i> Alur Pendaftaran (Maksimal 10 Langkah)</h6>
              <div id="alur_list">
                <?php 
                $steps = $ppdb_data['alur_pendaftaran'] ?? [];
                foreach ($steps as $idx => $s): 
                ?>
                  <div class="item-card alur-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-secondary">Langkah #<span class="item-number"><?= $idx + 1 ?></span></span>
                      <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small">Nama Langkah</label>
                      <input type="text" class="form-control form-control-sm" name="alur_name[]" value="<?= htmlspecialchars($s['title'] ?? '') ?>" required>
                    </div>
                    <div>
                      <label class="form-label small">Deskripsi Penjelasan</label>
                      <input type="text" class="form-control form-control-sm" name="alur_desc[]" value="<?= htmlspecialchars($s['description'] ?? '') ?>">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mt-2" id="btn-add-alur"><i class="bi bi-plus-circle"></i> Tambah Langkah</button>
            </div>

            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-file-earmark-check text-success"></i> Syarat Pendaftaran</h6>
              <div class="mb-3">
                <label class="form-label">Persyaratan Administrasi (Rich Text Editor)</label>
                <div id="quillSyarat" class="editor-container"></div>
                <input type="hidden" name="syarat_daftar" id="syaratDaftarVal">
              </div>
            </div>
          </div>

          <!-- TAB 6: KEAMANAN (`tab-keamanan`) -->
          <div class="tab-content-panel d-none" id="tab-keamanan">
            <div class="form-section-card">
              <h6 class="section-title"><i class="bi bi-key-fill text-danger"></i> Ganti Kata Sandi Administrator</h6>
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="admin" disabled>
              </div>
              <div class="mb-3">
                <label for="new_password" class="form-label">Kata Sandi Baru</label>
                <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Min. 6 karakter">
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Konfirmasikan kata sandi baru">
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- Toast notification modal -->
  <div class="toast-panel" id="actionToast">
    <i class="bi bi-check-circle-fill text-success fs-4" id="toastIcon"></i>
    <span class="toast-text" id="toastText">Pengaturan disimpan secara sukses!</span>
  </div>

  <!-- Quill rich text editor script -->
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

  <script>
    // Initialize Quill editors
    const quillVisi = new Quill('#quillVisi', { theme: 'snow', placeholder: 'Ketik Visi Sekolah...' });
    const quillMisi = new Quill('#quillMisi', { theme: 'snow', placeholder: 'Ketik Misi Sekolah...' });
    const quillSyarat = new Quill('#quillSyarat', { theme: 'snow', placeholder: 'Ketik Syarat PPDB...' });

    // Set initial values into editors
    document.addEventListener("DOMContentLoaded", () => {
      quillVisi.root.innerHTML = <?= json_encode($profile_data['vision_html'] ?? $profile_data['vision'] ?? '') ?>;
      const missionRaw = <?= json_encode(is_array($profile_data['mission'] ?? '') ? implode('<br>', $profile_data['mission']) : ($profile_data['mission_html'] ?? $profile_data['mission'] ?? '')) ?>;
      quillMisi.root.innerHTML = missionRaw;
      const syaratRaw = <?= json_encode(is_array($ppdb_data['persyaratan'] ?? '') ? implode('<br>', $ppdb_data['persyaratan']) : ($ppdb_data['syarat_html'] ?? $ppdb_data['persyaratan'] ?? '')) ?>;
      quillSyarat.root.innerHTML = syaratRaw;
    });

    // Tab Navigation Switch
    function switchTab(tabId) {
      document.querySelectorAll('.sidebar-menu .menu-link').forEach(link => {
        link.classList.remove('active');
      });
      document.getElementById('menu-' + tabId).classList.add('active');

      const tabs = ['global', 'home', 'profile', 'academic', 'ppdb', 'keamanan'];
      tabs.forEach(t => {
        const pane = document.getElementById('tab-' + t);
        if (pane) {
          if (t === tabId) {
            pane.classList.remove('d-none');
          } else {
            pane.classList.add('d-none');
          }
        }
      });

      const titleMap = {
        'global': 'Pengaturan Global',
        'home': 'Konten Beranda',
        'profile': 'Konten Profil',
        'academic': 'Konten Akademik',
        'ppdb': 'Konten PPDB',
        'keamanan': 'Keamanan Portal'
      };
      document.getElementById('panel-title-text').textContent = titleMap[tabId] || 'Pengaturan';
    }

    // Dynamic Lists configuration templates
    const slideTemplate = (idx) => `
      <div class="item-card slide-item">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-secondary">Slide #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="row g-3">
          <div class="col-md-4 mb-3">
            <label class="form-label small">Foto Latar Slide</label>
            <input type="file" class="form-control form-control-sm" name="hero_slide_image_${idx}" accept="image/*">
          </div>
          <div class="col-md-8">
            <div class="mb-2">
              <label class="form-label small">Headline Utama</label>
              <input type="text" class="form-control form-control-sm" name="hero_slide_headline[]" required>
            </div>
            <div class="mb-2">
              <label class="form-label small">Sub Headline</label>
              <input type="text" class="form-control form-control-sm" name="hero_slide_sub[]">
            </div>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label small">Teks CTA</label>
                <input type="text" class="form-control form-control-sm" name="hero_slide_cta_text[]" value="Pelajari Selengkapnya">
              </div>
              <div class="col-6">
                <label class="form-label small">Tautan CTA</label>
                <input type="text" class="form-control form-control-sm" name="hero_slide_cta_link[]" value="?page=ppdb">
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    const teacherTemplate = (idx) => `
      <div class="item-card guru-item">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-secondary">Guru #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="row g-3 align-items-center">
          <div class="col-md-3">
            <label class="form-label small">Foto Guru</label>
            <input type="file" class="form-control form-control-sm" name="teacher_photo_${idx}">
          </div>
          <div class="col-md-5">
            <label class="form-label small">Nama Lengkap & Gelar</label>
            <input type="text" class="form-control form-control-sm" name="teacher_name[]" required>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Mata Pelajaran / Jabatan</label>
            <input type="text" class="form-control form-control-sm" name="teacher_role[]">
          </div>
        </div>
      </div>
    `;

    const majorTemplate = (idx) => `
      <div class="card p-3 mb-3 major-item" style="background-color:rgba(0,0,0,0.02); border:1px solid var(--border-color); border-radius:10px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="badge bg-secondary">Jurusan #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="mb-2">
          <label class="form-label">Nama Program</label>
          <input type="text" class="form-control form-control-sm" name="major_name[]" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Deskripsi Singkat</label>
          <textarea class="form-control form-control-sm" name="major_desc[]" rows="2"></textarea>
        </div>
        <div>
          <label class="form-label">Ikon Class (Bootstrap Icons)</label>
          <input type="text" class="form-control form-control-sm" name="major_icon[]" value="bi-mortarboard-fill">
        </div>
      </div>
    `;

    const facilityTemplate = (idx) => `
      <div class="item-card facility-item">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-secondary">Fasilitas #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="row g-3 align-items-center">
          <div class="col-md-4">
            <label class="form-label small">Gambar Fasilitas</label>
            <input type="file" class="form-control form-control-sm" name="facility_photo_${idx}">
          </div>
          <div class="col-md-8">
            <label class="form-label small">Caption / Nama Fasilitas</label>
            <input type="text" class="form-control form-control-sm" name="facility_name[]" required>
          </div>
        </div>
      </div>
    `;

    const ekskulTemplate = (idx) => `
      <div class="item-card ekskul-item">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-secondary">Ekskul #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="row g-3 align-items-center">
          <div class="col-md-3">
            <label class="form-label small">Ikon / Gambar</label>
            <input type="file" class="form-control form-control-sm" name="ekskul_icon_${idx}">
          </div>
          <div class="col-md-5">
            <label class="form-label small">Nama Ekstrakurikuler</label>
            <input type="text" class="form-control form-control-sm" name="ekskul_name[]" required>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Jadwal Latihan</label>
            <input type="text" class="form-control form-control-sm" name="ekskul_schedule[]" value="Sabtu, 08:00 WIB">
          </div>
        </div>
      </div>
    `;

    const achievementTemplate = (idx) => `
      <div class="item-card prestasi-item">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-secondary">Prestasi #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="row g-3 align-items-center">
          <div class="col-md-3">
            <label class="form-label small">Bukti / Foto</label>
            <input type="file" class="form-control form-control-sm" name="achievement_photo_${idx}">
          </div>
          <div class="col-md-5">
            <label class="form-label small">Judul Penghargaan / Prestasi</label>
            <input type="text" class="form-control form-control-sm" name="achievement_title[]" required>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Tahun Penghargaan</label>
            <input type="number" class="form-control form-control-sm" name="achievement_year[]" value="2026">
          </div>
        </div>
      </div>
    `;

    const stepTemplate = (idx) => `
      <div class="item-card alur-item">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-secondary">Langkah #<span class="item-number">${idx + 1}</span></span>
          <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="mb-2">
          <label class="form-label small">Nama Langkah</label>
          <input type="text" class="form-control form-control-sm" name="alur_name[]" required>
        </div>
        <div>
          <label class="form-label small">Deskripsi Penjelasan</label>
          <input type="text" class="form-control form-control-sm" name="alur_desc[]">
        </div>
      </div>
    `;

    const missionTemplate = (idx) => `
      <div class="input-group mb-2 misi-item">
        <span class="input-group-text bg-secondary border-secondary text-white font-heading">Poin #<span class="item-number">${idx + 1}</span></span>
        <input type="text" class="form-control" name="about_mission[]" required>
        <button type="button" class="btn btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button>
      </div>
    `;

    function setupDynamicList(wrapperId, addBtnId, itemClass, limit, templateFn) {
      const wrapper = document.getElementById(wrapperId);
      const addBtn = document.getElementById(addBtnId);

      function renumberItems() {
        const items = wrapper.querySelectorAll('.' + itemClass);
        items.forEach((item, idx) => {
          const numSpan = item.querySelector('.item-number');
          if (numSpan) numSpan.textContent = idx + 1;
        });

        if (items.length >= limit) {
          addBtn.style.display = 'none';
        } else {
          addBtn.style.display = 'inline-block';
        }
      }

      addBtn.addEventListener('click', () => {
        const count = wrapper.querySelectorAll('.' + itemClass).length;
        if (count >= limit) return;

        const newHTML = templateFn(count);
        wrapper.insertAdjacentHTML('beforeend', newHTML);

        const newEl = wrapper.lastElementChild;
        newEl.querySelector('.btn-remove-item').addEventListener('click', () => {
          newEl.remove();
          renumberItems();
        });

        renumberItems();
      });

      wrapper.querySelectorAll('.' + itemClass).forEach(item => {
        item.querySelector('.btn-remove-item').addEventListener('click', () => {
          item.remove();
          renumberItems();
        });
      });

      renumberItems();
    }

    // Initialize lists
    setupDynamicList('hero-slides-list', 'btn-add-slide', 'slide-item', 5, slideTemplate);
    setupDynamicList('guru_list', 'btn-add-guru', 'guru-item', 10, teacherTemplate);
    setupDynamicList('majors-wrapper', 'btn-add-major', 'major-item', 10, majorTemplate);
    setupDynamicList('fasilitas_list', 'btn-add-facility', 'facility-item', 10, facilityTemplate);
    setupDynamicList('ekskul_list', 'btn-add-ekskul', 'ekskul-item', 10, ekskulTemplate);
    setupDynamicList('prestasi_list', 'btn-add-prestasi', 'prestasi-item', 10, achievementTemplate);
    setupDynamicList('alur_list', 'btn-add-alur', 'alur-item', 10, stepTemplate);

    // Toast show helper
    const actionToast = document.getElementById('actionToast');
    const toastText = document.getElementById('toastText');
    const toastIcon = document.getElementById('toastIcon');

    function showToast(message, isSuccess = true) {
      toastText.textContent = message;
      if (isSuccess) {
        toastIcon.className = "bi bi-check-circle-fill text-success fs-4";
        actionToast.style.borderLeftColor = "var(--success)";
      } else {
        toastIcon.className = "bi bi-exclamation-triangle-fill text-danger fs-4";
        actionToast.style.borderLeftColor = "#ef4444";
      }
      actionToast.classList.add('toast-show');
      setTimeout(() => {
        actionToast.classList.remove('toast-show');
      }, 3500);
    }

    // Local image previews helper
    function setupImagePreview(inputId, previewId, wrapperId) {
      const input = document.getElementById(inputId);
      const preview = document.getElementById(previewId);
      const wrapper = document.getElementById(wrapperId);

      input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          const localUrl = URL.createObjectURL(this.files[0]);
          preview.src = localUrl;
          wrapper.style.display = 'block';
        }
      });
    }

    setupImagePreview('logoInput', 'logoPreview', 'logoPreviewWrapper');
    setupImagePreview('kepsekPhotoInput', 'kepsekPhotoPreview', 'kepsekPhotoPreviewWrapper');
    setupImagePreview('historyImgInput', 'historyImgPreview', 'historyImgPreviewWrapper');

    // AJAX Form submission
    const saveBtn = document.getElementById('btnSaveAll');
    saveBtn.addEventListener('click', () => {
      try {
        const visiEl = document.getElementById('visiVal');
        const misiEl = document.getElementById('misiVal');
        const syaratEl = document.getElementById('syaratDaftarVal');

        if (visiEl && quillVisi) visiEl.value = quillVisi.root.innerHTML;
        if (misiEl && quillMisi) misiEl.value = quillMisi.root.innerHTML;
        if (syaratEl && quillSyarat) syaratEl.value = quillSyarat.root.innerHTML;

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...';

        const formEl = document.getElementById('settingsMasterForm');
        if (!formEl) {
          throw new Error('Form settingsMasterForm tidak ditemukan!');
        }

        // Manually report HTML5 validity (required fields validation)
        if (!formEl.checkValidity()) {
          formEl.reportValidity();
          saveBtn.disabled = false;
          saveBtn.innerHTML = '<i class="bi bi-floppy-fill"></i> <span>Simpan Perubahan (Save All to JSON)</span>';
          return;
        }

        const formData = new FormData(formEl);

        fetch('save_config.php', {
          method: 'POST',
          body: formData
        })
        .then(res => {
          if (!res.ok) throw new Error('Koneksi HTTP gagal (Status: ' + res.status + ')');
          return res.json();
        })
        .then(data => {
          saveBtn.disabled = false;
          saveBtn.innerHTML = '<i class="bi bi-floppy-fill"></i> <span>Simpan Perubahan (Save All to JSON)</span>';
          
          if (data.success) {
            showToast(data.message, true);
            if (data.reload) {
              setTimeout(() => window.location.reload(), 1000);
            }
          } else {
            showToast(data.message, false);
          }
        })
        .catch(err => {
          saveBtn.disabled = false;
          saveBtn.innerHTML = '<i class="bi bi-floppy-fill"></i> <span>Simpan Perubahan (Save All to JSON)</span>';
          showToast('Kesalahan Server: ' + err.message, false);
          console.error(err);
        });
      } catch (err) {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-floppy-fill"></i> <span>Simpan Perubahan (Save All to JSON)</span>';
        alert('Kesalahan Client JS: ' + err.message);
        console.error(err);
      }
    });
  </script>
</body>
</html>
