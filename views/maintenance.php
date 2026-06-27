<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Situs Sedang Pemeliharaan | <?= htmlspecialchars($site_settings['sekolah_nama'] ?? 'Sekolah') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #090d16 0%, #1e1b4b 100%);
      color: #f8fafc;
      font-family: 'Outfit', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .maintenance-card {
      max-width: 600px;
      background: rgba(19, 26, 38, 0.7);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 24px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.4);
      padding: 40px;
      text-align: center;
      margin: 15px;
    }
    .pulse-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #06b6d4 0%, #d946ef 100%);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
      animation: pulse 2s infinite;
      margin-bottom: 24px;
    }
    @keyframes pulse {
      0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.4); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(6, 182, 212, 0); }
      100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(6, 182, 212, 0); }
    }
  </style>
</head>
<body>
  <div class="maintenance-card">
    <div class="pulse-icon">
      <i class="bi bi-gear-wide-connected text-white" style="font-size: 40px;"></i>
    </div>
    <h2 class="fw-bold mb-2 text-white"><?= htmlspecialchars($site_settings['sekolah_nama'] ?? 'Sekolah Nusantara Mandiri') ?></h2>
    <h4 class="text-info fw-semibold mb-3">Situs Sedang Dalam Pemeliharaan</h4>
    <p class="text-muted mb-4">Kami sedang melakukan pemeliharaan server rutin untuk meningkatkan performa layanan siber sekolah. Mohon maaf atas ketidaknyamanannya. Hubungi kami jika ada hal mendesak.</p>
    <div>
      <a href="dashboard/index.php" class="btn btn-primary rounded-pill px-4 py-2">Portal Admin</a>
    </div>
  </div>
</body>
</html>
