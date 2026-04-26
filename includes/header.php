<?php
/* ============================================================
 * START: Session + Security Bootstrap
 * Purpose: Enforce authenticated access and initialize locale.
 * Why: All protected pages must validate session consistently.
 * ============================================================ */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /public/index.php?page=login');
    exit;
}

if (!isset($_SESSION['locale']) || !in_array($_SESSION['locale'], ['en', 'fr'], true)) {
    $_SESSION['locale'] = 'en';
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr'], true)) {
    $_SESSION['locale'] = $_GET['lang'];
}
/* ============================================================
 * END: Session + Security Bootstrap
 * ============================================================ */

/* ============================================================
 * START: Silent Error Handling
 * Purpose: Log errors without exposing details to end users.
 * Why: Prevent leakage of sensitive internal information.
 * ============================================================ */
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
/* ============================================================
 * END: Silent Error Handling
 * ============================================================ */

/* ============================================================
 * START: Localization Loader + Translator
 * Purpose: Load locale file and expose t('key') helper.
 * Why: All visible labels must support EN/FR translations.
 * ============================================================ */
$locale = $_SESSION['locale'];
$langFile = __DIR__ . '/../lang/' . $locale . '.php';
$translations = is_file($langFile) ? require $langFile : [];

if (!function_exists('t')) {
    function t(string $key): string
    {
        global $translations;
        return htmlspecialchars((string)($translations[$key] ?? $key), ENT_QUOTES, 'UTF-8');
    }
}
/* ============================================================
 * END: Localization Loader + Translator
 * ============================================================ */

/* ============================================================
 * START: Header Variables Defaults
 * Purpose: Set default values for shared header UI variables.
 * Why: Keeps partials reusable across all protected pages.
 * ============================================================ */
$pageTitle = isset($pageTitle) ? (string)$pageTitle : 'Dashboard';
$escapedPageTitle = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8');
$userDisplayName = htmlspecialchars((string)($_SESSION['user_name'] ?? 'User'), ENT_QUOTES, 'UTF-8');
$userRole = htmlspecialchars((string)($_SESSION['user_role'] ?? 'Organization Administrator'), ENT_QUOTES, 'UTF-8');
$userInitial = strtoupper(substr($userDisplayName, 0, 1));
/* ============================================================
 * END: Header Variables Defaults
 * ============================================================ */
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($locale, ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escapedPageTitle; ?> | Macerti</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body class="app-body">
<div class="app-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="app-main-wrapper">
        <nav class="navbar navbar-expand-lg app-topbar border-bottom bg-white sticky-top">
            <div class="container-fluid px-3 px-lg-4">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="mobileSidebarToggle" type="button" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <h1 class="h5 mb-0 ms-2 ms-lg-0"><?= $escapedPageTitle; ?></h1>

                <div class="ms-auto d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light" id="darkModeToggle" type="button" data-bs-toggle="tooltip" data-bs-title="<?= t('toggle_dark_mode'); ?>" aria-label="<?= t('toggle_dark_mode'); ?>">
                        <i class="bi bi-moon-stars"></i>
                    </button>

                    <div class="btn-group" role="group" aria-label="Language switcher">
                        <a class="btn btn-sm <?= $locale === 'en' ? 'btn-primary' : 'btn-outline-secondary'; ?>" href="?lang=en"><?= t('lang_en'); ?></a>
                        <a class="btn btn-sm <?= $locale === 'fr' ? 'btn-primary' : 'btn-outline-secondary'; ?>" href="?lang=fr"><?= t('lang_fr'); ?></a>
                    </div>

                    <button type="button" class="btn btn-sm btn-light position-relative" data-bs-toggle="tooltip" data-bs-title="<?= t('notifications'); ?>" aria-label="<?= t('notifications'); ?>">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar-circle"><?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="d-none d-md-inline"><?= $userDisplayName; ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i><?= t('profile'); ?></a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i><?= t('settings'); ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="public/index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i><?= t('logout'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
