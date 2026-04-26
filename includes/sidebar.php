<?php
/* ============================================================
 * START: Sidebar Navigation Configuration
 * Purpose: Define primary navigation links and icons.
 * Why: Centralized config keeps sidebar maintainable and translatable.
 * ============================================================ */
$navItems = [
    ['key' => 'dashboard', 'icon' => 'bi-grid-1x2', 'href' => 'index.php'],
    ['key' => 'standards_compliance', 'icon' => 'bi-shield-check', 'href' => '#'],
    ['key' => 'documents', 'icon' => 'bi-folder2-open', 'href' => '#'],
    ['key' => 'processes', 'icon' => 'bi-diagram-3', 'href' => '#'],
    ['key' => 'risks_issues', 'icon' => 'bi-exclamation-triangle', 'href' => '#'],
    ['key' => 'audits', 'icon' => 'bi-clipboard2-check', 'href' => '#'],
    ['key' => 'actions', 'icon' => 'bi-list-task', 'href' => '#'],
    ['key' => 'objectives', 'icon' => 'bi-bullseye', 'href' => '#'],
    ['key' => 'management_review', 'icon' => 'bi-people', 'href' => '#'],
    ['key' => 'settings', 'icon' => 'bi-gear', 'href' => '#'],
];
/* ============================================================
 * END: Sidebar Navigation Configuration
 * ============================================================ */
?>

<aside class="app-sidebar" id="appSidebar">
    <div class="d-flex flex-column h-100">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <span class="brand-logo"><i class="bi bi-building"></i></span>
                <div class="brand-text-wrap">
                    <div class="brand-title">Macerti</div>
                    <small class="text-muted">Management System</small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light d-none d-lg-inline-flex" id="desktopSidebarToggle" data-bs-toggle="tooltip" data-bs-title="<?= t('collapse_sidebar'); ?>" aria-label="<?= t('collapse_sidebar'); ?>">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>
        </div>

        <nav class="sidebar-nav flex-grow-1">
            <ul class="nav nav-pills flex-column gap-1" id="sidebarNav">
                <?php foreach ($navItems as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2" data-nav-key="<?= htmlspecialchars($item['key'], ENT_QUOTES, 'UTF-8'); ?>" href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="bi <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            <span class="nav-label"><?= t($item['key']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="sidebar-user mt-auto">
            <div class="d-flex align-items-center gap-2">
                <span class="avatar-circle"><?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                <div class="overflow-hidden sidebar-user-meta">
                    <div class="fw-semibold text-truncate"><?= $userDisplayName; ?></div>
                    <small class="text-muted text-truncate"><?= $userRole; ?></small>
                </div>
            </div>
        </div>
    </div>
</aside>

<nav class="bottom-nav d-md-none" id="mobileBottomNav">
    <a href="index.php" class="bottom-nav-item" data-nav-key="dashboard"><i class="bi bi-grid-1x2"></i><span><?= t('dashboard'); ?></span></a>
    <a href="#" class="bottom-nav-item" data-nav-key="standards_compliance"><i class="bi bi-shield-check"></i><span><?= t('standards'); ?></span></a>
    <a href="#" class="bottom-nav-item" data-nav-key="documents"><i class="bi bi-folder2-open"></i><span><?= t('documents'); ?></span></a>
    <a href="#" class="bottom-nav-item" data-nav-key="audits"><i class="bi bi-clipboard2-check"></i><span><?= t('audits'); ?></span></a>
    <a href="#" class="bottom-nav-item" data-nav-key="settings"><i class="bi bi-gear"></i><span><?= t('settings'); ?></span></a>
</nav>
