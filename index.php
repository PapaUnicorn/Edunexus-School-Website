<?php
// 1. Capture request page (default to 'home')
$page = isset($_GET['page']) ? trim($_GET['page']) : 'home';

// 2. Whitelisted pages
$allowed_pages = ['home', 'profile', 'academic', 'contact', 'ppdb'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// 3. Load global site configuration database
$siteSettingsFile = 'config/site_settings.json';
$site_settings = [];
if (file_exists($siteSettingsFile)) {
    $site_settings = json_decode(file_get_contents($siteSettingsFile), true) ?? [];
}

// 4. Handle Maintenance Mode
if (($site_settings['status_maintenance'] ?? 'false') === 'true') {
    require_once 'views/maintenance.php';
    exit;
}

// 5. Load page-specific content database
$pageDataFile = "config/{$page}.json";
$page_data = [];
if (file_exists($pageDataFile)) {
    $page_data = json_decode(file_get_contents($pageDataFile), true) ?? [];
}

// 6. Render dynamic page template
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$viewFile = "views/{$page}.php";
if (file_exists($viewFile)) {
    require_once $viewFile;
} else {
    echo "<div class='container py-5 text-center mt-5'><h3 class='text-muted'>Halaman tidak ditemukan.</h3></div>";
}

require_once 'includes/footer.php';
?>
