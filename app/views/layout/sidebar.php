<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$nav = [
    'dashboard' => 'Dashboard',
    'standards-compliance' => 'Standards & Compliance',
    'documents' => 'Documents',
    'processes' => 'Processes',
    'risks-issues' => 'Risks & Issues',
    'audits' => 'Audits',
    'actions' => 'Actions',
    'objectives' => 'Objectives',
    'management-review' => 'Management Review',
    'settings' => 'Settings',
    'roles' => 'Roles',
    'profile' => 'Profile',
];
?>
<aside>
    <h3>Navigation</h3>
    <ul>
        <?php foreach ($nav as $key => $label): ?>
            <?php if (!can_access_module($key)): ?>
                <?php continue; ?>
            <?php endif; ?>
            <li><a href="index.php?page=<?= e($key); ?>"<?= $currentPage === $key ? ' aria-current="page"' : ''; ?>><?= e($label); ?></a></li>
        <?php endforeach; ?>
        <li><a href="index.php?page=logout">Logout</a></li>
    </ul>
</aside>
