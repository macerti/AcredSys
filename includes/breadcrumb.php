<?php
/* ============================================================
 * START: Breadcrumb Input Normalization
 * Purpose: Accept a passed breadcrumb array or fallback default.
 * Why: Keeps include robust even if caller forgets to pass data.
 * ============================================================ */
$breadcrumb = isset($breadcrumb) && is_array($breadcrumb)
    ? $breadcrumb
    : [
        ['label' => t('dashboard'), 'url' => 'index.php'],
    ];
/* ============================================================
 * END: Breadcrumb Input Normalization
 * ============================================================ */
?>

<div class="app-breadcrumb sticky-top bg-white border-bottom rounded-2 mb-3 mb-lg-4">
    <nav aria-label="breadcrumb" class="px-3 py-2">
        <ol class="breadcrumb mb-0">
            <?php foreach ($breadcrumb as $index => $crumb): ?>
                <?php $isLast = $index === array_key_last($breadcrumb); ?>
                <li class="breadcrumb-item <?= $isLast ? 'active' : ''; ?>" <?= $isLast ? 'aria-current="page"' : ''; ?>>
                    <?php if (!$isLast && !empty($crumb['url'])): ?>
                        <a href="<?= htmlspecialchars((string)$crumb['url'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars((string)$crumb['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php else: ?>
                        <?= htmlspecialchars((string)$crumb['label'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>
