<?php
session_start();
$credentials = require_once 'credentials.php';

// Redirect to dashboard index if already authenticated
if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === $credentials['username'] && password_verify($password, $credentials['password_hash'])) {
        $_SESSION['auth'] = true;
        $_SESSION['last_activity'] = time();
        header("Location: index.php");
        exit;
    } else {
        $error = 'Nama pengguna atau kata sandi tidak valid.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Edunexus CMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #090d16 0%, #111827 100%);
      color: #f8fafc;
      font-family: 'Outfit', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .login-card {
      width: 100%;
      max-width: 420px;
      background: rgba(19, 26, 38, 0.85);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 24px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.5);
      padding: 40px;
    }
    .brand-icon {
      background: linear-gradient(135deg, #06b6d4 0%, #d946ef 100%);
      color: white;
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
    }
    .form-control {
      background-color: #090d16;
      border: 1px solid #202b3c;
      color: white;
      border-radius: 12px;
      padding: 12px 16px;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      background-color: #090d16;
      border-color: #06b6d4;
      box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.25);
      color: white;
    }
    .btn-login {
      background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
      border: none;
      color: white;
      border-radius: 12px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-login:hover {
      filter: brightness(1.1);
      transform: translateY(-1px);
    }
  </style>
</head>
<body>
  <div class="login-card text-center">
    <div class="brand-icon">
      <i class="bi bi-shield-lock-fill text-white fs-4"></i>
    </div>
    <h3 class="fw-bold mb-1 text-white">Portal Admin</h3>
    <p class="text-muted small mb-4">Masuk untuk mengelola konfigurasi sekolah</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2 px-3 small border-0 text-start d-flex align-items-center gap-2 mb-3" style="background-color: rgba(239, 68, 68, 0.1); color: #f87171;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['timeout'])): ?>
      <div class="alert alert-warning py-2 px-3 small border-0 text-start d-flex align-items-center gap-2 mb-3" style="background-color: rgba(245, 158, 11, 0.1); color: #fbbf24;">
        <i class="bi bi-hourglass-split"></i>
        <span>Sesi berakhir karena tidak ada aktivitas.</span>
      </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="text-start mb-3">
        <label for="username" class="form-label text-muted small fw-semibold">Nama Pengguna</label>
        <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username" required>
      </div>
      <div class="text-start mb-4">
        <label for="password" class="form-label text-muted small fw-semibold">Kata Sandi</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required>
      </div>
      <button type="submit" class="btn btn-login w-100 mb-3">Masuk Sekarang</button>
      <a href="../index.php" class="text-muted small text-decoration-none d-inline-block"><i class="bi bi-arrow-left"></i> Kembali ke Website</a>
    </form>
  </div>
</body>
</html>
