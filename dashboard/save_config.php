<?php
session_start();
header('Content-Type: application/json');

// Strict Authentication Check
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Sesi tidak valid. Akses ditolak.']);
    exit;
}

// Inactivity check
$inactive = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    echo json_encode(['success' => false, 'message' => 'Sesi kedaluwarsa.', 'reload' => true]);
    exit;
}
$_SESSION['last_activity'] = time();

$configDir = '../config/';
$uploadDir = '../assets/images/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Extract Quill HTML to plain text array items helper
function htmlToArray($html) {
    $array = [];
    if (!empty($html)) {
        preg_match_all('/<li>(.*?)<\/li>/i', $html, $matches);
        if (!empty($matches[1])) {
            $array = array_map('strip_tags', $matches[1]);
        } else {
            preg_match_all('/<p>(.*?)<\/p>/i', $html, $matches);
            if (!empty($matches[1])) {
                $array = array_map('strip_tags', $matches[1]);
            } else {
                $array = array_filter(array_map('trim', explode("\n", strip_tags($html))));
            }
        }
    }
    return array_values($array);
}

// Load existing config files
$site_settings = json_decode(file_get_contents($configDir . 'site_settings.json'), true) ?? [];
$home_data = json_decode(file_get_contents($configDir . 'home.json'), true) ?? [];
$profile_data = json_decode(file_get_contents($configDir . 'profile.json'), true) ?? [];
$academic_data = json_decode(file_get_contents($configDir . 'academic.json'), true) ?? [];
$ppdb_data = json_decode(file_get_contents($configDir . 'ppdb.json'), true) ?? [];
$contact_data = json_decode(file_get_contents($configDir . 'contact.json'), true) ?? [];

// File uploading validators
$allowedExtensions = ['png', 'jpg', 'jpeg', 'svg', 'gif', 'webp', 'ico'];
$maxFileSize = 2 * 1024 * 1024; // 2MB

// Process single upload helper
function processSingleUpload($fieldName, &$configTarget, $configKey, $defaultBaseName, $uploadDir, $allowedExtensions, $maxFileSize) {
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$fieldName];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($file['size'] > $maxFileSize) {
            echo json_encode(['success' => false, 'message' => "Ukuran berkas $fieldName melebihi batas maksimal 2MB."]);
            exit;
        }

        if (!in_array($fileExt, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => "Format berkas $fieldName tidak valid. Gunakan PNG, JPG, SVG, atau WEBP."]);
            exit;
        }

        $newFilename = $defaultBaseName . '_' . time() . '.' . $fileExt;
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
            $oldPath = $configTarget[$configKey] ?? '';
            if (!empty($oldPath) && file_exists('../' . $oldPath) && is_file('../' . $oldPath)) {
                if (strpos($oldPath, 'placeholder') === false && strpos($oldPath, 'placehold.co') === false) {
                    unlink('../' . $oldPath);
                }
            }
            $configTarget[$configKey] = 'assets/images/' . $newFilename;
        }
    }
}

// 1. Core Uploads
processSingleUpload('foto_kepsek', $home_data['kepala_sekolah'], 'foto', 'kepsek', $uploadDir, $allowedExtensions, $maxFileSize);
processSingleUpload('logo', $site_settings, 'logo_url', 'logo-sekolah', $uploadDir, $allowedExtensions, $maxFileSize);
processSingleUpload('gambar_sejarah', $profile_data, 'history_image_url', 'history', $uploadDir, $allowedExtensions, $maxFileSize);

// 2. Kalender Akademik PDF Upload
if (isset($_FILES['kalender_akademik']) && $_FILES['kalender_akademik']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['kalender_akademik'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($fileExt === 'pdf' && $file['size'] <= 5 * 1024 * 1024) {
        $newFilename = 'calendar_' . time() . '.pdf';
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
            $oldPath = $academic_data['calendar_url'] ?? '';
            if (!empty($oldPath) && file_exists('../' . $oldPath) && is_file('../' . $oldPath)) {
                unlink('../' . $oldPath);
            }
            $academic_data['calendar_url'] = 'assets/images/' . $newFilename;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Kalender akademik harus berupa file PDF dengan ukuran maksimal 5MB.']);
        exit;
    }
}

// 3. Mapping Text Form Values
if (isset($_POST['sekolah_nama'])) {
    
    // Site Settings (Global)
    $site_settings['sekolah_nama'] = sanitize($_POST['sekolah_nama']);
    $site_settings['npsn'] = sanitize($_POST['npsn'] ?? '');
    $site_settings['warna_brand'] = sanitize($_POST['warna_brand'] ?? '#3b82f6');
    $site_settings['tema_aktif'] = sanitize($_POST['tema_aktif'] ?? 'tema-nova');
    $site_settings['status_maintenance'] = sanitize($_POST['status_maintenance'] ?? 'false');
    
    $site_settings['social']['instagram'] = sanitize($_POST['social_instagram'] ?? '');
    $site_settings['social']['facebook'] = sanitize($_POST['social_facebook'] ?? '');
    $site_settings['social']['tiktok'] = sanitize($_POST['social_tiktok'] ?? '');
    $site_settings['social']['youtube'] = sanitize($_POST['social_youtube'] ?? '');

    $site_settings['api']['blogger_id'] = sanitize($_POST['blogger_blog_id'] ?? '');
    $site_settings['api']['blogger_key'] = sanitize($_POST['blogger_api_key'] ?? '');

    // Home
    // Slideshow Carousel list mapping
    $slides = [];
    if (isset($_POST['hero_slide_headline']) && is_array($_POST['hero_slide_headline'])) {
        foreach ($_POST['hero_slide_headline'] as $idx => $headline) {
            $headlineVal = sanitize($headline);
            if (!empty($headlineVal)) {
                $imgPath = '';
                $existingSlides = $home_data['hero_slides'] ?? [];
                if (empty($existingSlides) && !empty($home_data['hero_banner'])) {
                    $existingSlides = [$home_data['hero_banner']];
                }
                if (isset($existingSlides[$idx]['image_url'])) {
                    $imgPath = $existingSlides[$idx]['image_url'];
                }

                $fileField = "hero_slide_image_$idx";
                if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fileField];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (in_array($fileExt, $allowedExtensions) && $file['size'] <= $maxFileSize) {
                        $newFilename = 'hero_slide_' . $idx . '_' . time() . '.' . $fileExt;
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
                            if (!empty($imgPath) && file_exists('../' . $imgPath) && is_file('../' . $imgPath)) {
                                if (strpos($imgPath, 'placeholder') === false && strpos($imgPath, 'banner-utama') === false) {
                                    unlink('../' . $imgPath);
                                }
                            }
                            $imgPath = 'assets/images/' . $newFilename;
                        }
                    }
                }
                $slides[] = [
                    'image_url' => $imgPath,
                    'headline' => $headlineVal,
                    'sub_hero' => sanitize($_POST['hero_slide_sub'][$idx] ?? ''),
                    'cta_text' => sanitize($_POST['hero_slide_cta_text'][$idx] ?? ''),
                    'cta_link' => sanitize($_POST['hero_slide_cta_link'][$idx] ?? '')
                ];
            }
        }
    }
    $home_data['hero_slides'] = $slides;

    $home_data['kepala_sekolah']['nama'] = sanitize($_POST['kepsek_nama'] ?? '');
    $home_data['kepala_sekolah']['peran'] = sanitize($_POST['kepsek_jabatan'] ?? 'Kepala Sekolah');
    $home_data['kepala_sekolah']['sambutan'] = sanitize($_POST['kepsek_sambutan'] ?? '');

    $home_data['stats']['siswa_aktif'] = (int)($_POST['stat_siswa'] ?? 0);
    $home_data['stats']['guru_staf'] = (int)($_POST['stat_guru'] ?? 0);
    $home_data['stats']['ekstrakurikuler'] = (int)($_POST['stat_ekskul'] ?? 0);
    $home_data['stats']['alumni'] = (int)($_POST['stat_alumni'] ?? 0);

    // Profile (Quill Rich Text & Sejarah)
    $profile_data['about_title'] = sanitize($_POST['about_title'] ?? 'Tentang Kami');
    $profile_data['about_description'] = sanitize($_POST['about_description'] ?? '');
    $profile_data['sejarah'] = sanitize($_POST['teks_sejarah'] ?? '');
    
    $profile_data['vision_html'] = $_POST['visi'] ?? '';
    $profile_data['vision'] = strip_tags($_POST['visi'] ?? '');
    
    $profile_data['mission_html'] = $_POST['misi'] ?? '';
    $profile_data['mission'] = htmlToArray($_POST['misi'] ?? '');

    // Teacher dynamic list mapping
    $teachers = [];
    if (isset($_POST['teacher_name']) && is_array($_POST['teacher_name'])) {
        foreach ($_POST['teacher_name'] as $idx => $name) {
            $nameVal = sanitize($name);
            if (!empty($nameVal)) {
                $photoPath = '';
                $existing = $profile_data['teachers'] ?? [];
                if (isset($existing[$idx]['photo'])) {
                    $photoPath = $existing[$idx]['photo'];
                }
                
                $fileField = "teacher_photo_$idx";
                if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fileField];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (in_array($fileExt, $allowedExtensions) && $file['size'] <= $maxFileSize) {
                        $newFilename = 'teacher_' . $idx . '_' . time() . '.' . $fileExt;
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
                            if (!empty($photoPath) && file_exists('../' . $photoPath) && is_file('../' . $photoPath)) {
                                if (strpos($photoPath, 'placeholder') === false) unlink('../' . $photoPath);
                            }
                            $photoPath = 'assets/images/' . $newFilename;
                        }
                    }
                }
                $teachers[] = [
                    'name' => $nameVal,
                    'role' => sanitize($_POST['teacher_role'][$idx] ?? ''),
                    'photo' => $photoPath
                ];
            }
        }
    }
    $profile_data['teachers'] = $teachers;

    // Academic Kurikulum description
    $academic_data['kurikulum_desc'] = sanitize($_POST['deskripsi_kurikulum'] ?? '');
    
    // Majors dynamic list mapping
    $majors = [];
    if (isset($_POST['major_name']) && is_array($_POST['major_name'])) {
        foreach ($_POST['major_name'] as $idx => $name) {
            $nameVal = sanitize($name);
            if (!empty($nameVal)) {
                $majors[] = [
                    'name' => $nameVal,
                    'description' => sanitize($_POST['major_desc'][$idx] ?? ''),
                    'icon' => sanitize($_POST['major_icon'][$idx] ?? 'bi-mortarboard-fill')
                ];
            }
        }
    }
    $academic_data['majors'] = $majors;

    // Facilities dynamic list mapping
    $facilities = [];
    if (isset($_POST['facility_name']) && is_array($_POST['facility_name'])) {
        foreach ($_POST['facility_name'] as $idx => $name) {
            $nameVal = sanitize($name);
            if (!empty($nameVal)) {
                $imgPath = '';
                $existing = $academic_data['facilities'] ?? [];
                if (isset($existing[$idx]['image'])) {
                    $imgPath = $existing[$idx]['image'];
                }

                $fileField = "facility_photo_$idx";
                if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fileField];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (in_array($fileExt, $allowedExtensions) && $file['size'] <= $maxFileSize) {
                        $newFilename = 'facility_' . $idx . '_' . time() . '.' . $fileExt;
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
                            if (!empty($imgPath) && file_exists('../' . $imgPath) && is_file('../' . $imgPath)) {
                                if (strpos($imgPath, 'placeholder') === false) unlink('../' . $imgPath);
                            }
                            $imgPath = 'assets/images/' . $newFilename;
                        }
                    }
                }
                $facilities[] = [
                    'name' => $nameVal,
                    'image' => $imgPath
                ];
            }
        }
    }
    $academic_data['facilities'] = $facilities;

    // Extracurriculars dynamic list mapping
    $ekskuls = [];
    if (isset($_POST['ekskul_name']) && is_array($_POST['ekskul_name'])) {
        foreach ($_POST['ekskul_name'] as $idx => $name) {
            $nameVal = sanitize($name);
            if (!empty($nameVal)) {
                $iconPath = '';
                $existing = $academic_data['extracurriculars'] ?? [];
                if (isset($existing[$idx]['image'])) {
                    $iconPath = $existing[$idx]['image'];
                }

                $fileField = "ekskul_icon_$idx";
                if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fileField];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (in_array($fileExt, $allowedExtensions) && $file['size'] <= $maxFileSize) {
                        $newFilename = 'ekskul_' . $idx . '_' . time() . '.' . $fileExt;
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
                            if (!empty($iconPath) && file_exists('../' . $iconPath) && is_file('../' . $iconPath)) {
                                if (strpos($iconPath, 'placeholder') === false) unlink('../' . $iconPath);
                            }
                            $iconPath = 'assets/images/' . $newFilename;
                        }
                    }
                }
                $ekskuls[] = [
                    'name' => $nameVal,
                    'schedule' => sanitize($_POST['ekskul_schedule'][$idx] ?? 'Sabtu, 08:00 WIB'),
                    'image' => $iconPath
                ];
            }
        }
    }
    $academic_data['extracurriculars'] = $ekskuls;

    // Achievements dynamic list mapping
    $achievements = [];
    if (isset($_POST['achievement_title']) && is_array($_POST['achievement_title'])) {
        foreach ($_POST['achievement_title'] as $idx => $title) {
            $titleVal = sanitize($title);
            if (!empty($titleVal)) {
                $imgPath = '';
                $existing = $academic_data['achievements'] ?? [];
                if (isset($existing[$idx]['image'])) {
                    $imgPath = $existing[$idx]['image'];
                }

                $fileField = "achievement_photo_$idx";
                if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fileField];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (in_array($fileExt, $allowedExtensions) && $file['size'] <= $maxFileSize) {
                        $newFilename = 'achievement_' . $idx . '_' . time() . '.' . $fileExt;
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFilename)) {
                            if (!empty($imgPath) && file_exists('../' . $imgPath) && is_file('../' . $imgPath)) {
                                if (strpos($imgPath, 'placeholder') === false) unlink('../' . $imgPath);
                            }
                            $imgPath = 'assets/images/' . $newFilename;
                        }
                    }
                }
                $achievements[] = [
                    'title' => $titleVal,
                    'year' => (int)($_POST['achievement_year'][$idx] ?? date('Y')),
                    'image' => $imgPath
                ];
            }
        }
    }
    $academic_data['achievements'] = $achievements;

    // PPDB Config (Status switch & Alur dynamic list & requirements Quill)
    $ppdb_data['status_pendaftaran'] = isset($_POST['ppdb_status']) ? 'buka' : 'tutup';
    $ppdb_data['cta_link'] = sanitize($_POST['ppdb_cta_link'] ?? '');
    
    $ppdb_data['syarat_html'] = $_POST['syarat_daftar'] ?? '';
    $ppdb_data['persyaratan'] = htmlToArray($_POST['syarat_daftar'] ?? '');

    $alur = [];
    if (isset($_POST['alur_name']) && is_array($_POST['alur_name'])) {
        foreach ($_POST['alur_name'] as $idx => $title) {
            $titleVal = sanitize($title);
            if (!empty($titleVal)) {
                $alur[] = [
                    'step' => (string)($idx + 1),
                    'title' => $titleVal,
                    'description' => sanitize($_POST['alur_desc'][$idx] ?? '')
                ];
            }
        }
    }
    $ppdb_data['alur_pendaftaran'] = $alur;

    // Contact & Maps
    $contact_data['address'] = sanitize($_POST['contact_address'] ?? '');
    $contact_data['phone'] = sanitize($_POST['contact_phone'] ?? '');
    $contact_data['email'] = sanitize($_POST['contact_email'] ?? '');
    $contact_data['gmaps_url'] = trim($_POST['contact_map'] ?? '');
}

// Password Change Handling
$reload = false;
if (!empty($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Konfirmasi kata sandi tidak cocok.']);
        exit;
    }

    if (strlen($newPassword) < 6) {
        echo json_encode(['success' => false, 'message' => 'Kata sandi baru minimal 6 karakter.']);
        exit;
    }

    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $credentialsContent = "<?php\n" .
                           "// Password asli untuk setup: AdminSekolah2026\n" .
                           "return [\n" .
                           "    'username' => 'admin',\n" .
                           "    'password_hash' => '" . addslashes($newHash) . "'\n" .
                           "];\n" .
                           "?>\n";

    if (file_put_contents('credentials.php', $credentialsContent) !== false) {
        $reload = true;
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengubah kata sandi karena masalah hak akses berkas credentials.php.']);
        exit;
    }
}

// Write back configuration JSONs
$saveStatus = true;
$saveStatus &= (file_put_contents($configDir . 'site_settings.json', json_encode($site_settings, JSON_PRETTY_PRINT)) !== false);
$saveStatus &= (file_put_contents($configDir . 'home.json', json_encode($home_data, JSON_PRETTY_PRINT)) !== false);
$saveStatus &= (file_put_contents($configDir . 'profile.json', json_encode($profile_data, JSON_PRETTY_PRINT)) !== false);
$saveStatus &= (file_put_contents($configDir . 'academic.json', json_encode($academic_data, JSON_PRETTY_PRINT)) !== false);
$saveStatus &= (file_put_contents($configDir . 'ppdb.json', json_encode($ppdb_data, JSON_PRETTY_PRINT)) !== false);
$saveStatus &= (file_put_contents($configDir . 'contact.json', json_encode($contact_data, JSON_PRETTY_PRINT)) !== false);

if ($saveStatus) {
    echo json_encode(['success' => true, 'message' => 'Semua pengaturan berhasil disimpan ke berkas JSON!', 'reload' => $reload]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan perubahan ke database flat-file.']);
}
exit;
?>
