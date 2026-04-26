<?php
/* ============================================================
 * START: Dashboard Page Controller Variables
 * Purpose: Define view-level placeholders until DB wiring phase.
 * Why: Keeps layout phase independent from data integration phase.
 * ============================================================ */
$pageTitle = 'Dashboard';
// TODO: fetch from DB
$kpis = [
    'total_standards' => 12,
    'open_nonconformities' => 8,
    'pending_actions' => 15,
    'upcoming_audits' => 4,
];
// TODO: fetch from DB
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => 'index.php'],
];
/* ============================================================
 * END: Dashboard Page Controller Variables
 * ============================================================ */

require __DIR__ . '/includes/header.php';
// TODO: fetch from DB
$userFirstName = $_SESSION['first_name'] ?? 'Alex';
?>

<main class="app-content px-3 px-lg-4 py-3 py-lg-4" id="appContent">
    <?php require __DIR__ . '/includes/breadcrumb.php'; ?>

    <section class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="h4 mb-1"><?= t('welcome_back'); ?>, <?= htmlspecialchars((string)$userFirstName, ENT_QUOTES, 'UTF-8'); ?>.</h2>
                <p class="text-muted mb-0">Overview of your management system performance.</p>
            </div>
            <button class="btn btn-outline-primary" type="button" onclick="showToast('Dashboard shell loaded successfully', 'success')">Show Toast</button>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2"><?= t('total_standards'); ?></p>
                    <!-- TODO: replace with real data -->
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string)$kpis['total_standards'], ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2"><?= t('open_nonconformities'); ?></p>
                    <!-- TODO: replace with real data -->
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string)$kpis['open_nonconformities'], ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2"><?= t('pending_actions'); ?></p>
                    <!-- TODO: replace with real data -->
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string)$kpis['pending_actions'], ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2"><?= t('upcoming_audits'); ?></p>
                    <!-- TODO: replace with real data -->
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string)$kpis['upcoming_audits'], ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>
            </article>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <article class="card h-100">
                <div class="card-header bg-transparent border-bottom-0 pb-0">
                    <h3 class="h6 mb-0"><?= t('compliance_trends'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="placeholder-chart">
                        <canvas aria-label="Compliance Trends Placeholder" role="img"></canvas>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-12 col-xl-6">
            <article class="card h-100">
                <div class="card-header bg-transparent border-bottom-0 pb-0">
                    <h3 class="h6 mb-0"><?= t('audit_pipeline'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="placeholder-chart">
                        <canvas aria-label="Audit Pipeline Placeholder" role="img"></canvas>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <section class="card">
        <div class="card-header bg-transparent">
            <h3 class="h6 mb-0"><?= t('recent_activity'); ?></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                        <th>User</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <!-- TODO: replace with real data -->
                        <td>2026-04-26</td>
                        <!-- TODO: replace with real data -->
                        <td>Internal audit schedule updated</td>
                        <!-- TODO: replace with real data -->
                        <td>Sarah Lee</td>
                        <!-- TODO: replace with real data -->
                        <td><span class="badge text-bg-success">Completed</span></td>
                    </tr>
                    <tr>
                        <!-- TODO: replace with real data -->
                        <td>2026-04-25</td>
                        <!-- TODO: replace with real data -->
                        <td>Corrective action assigned for NC-2026-014</td>
                        <!-- TODO: replace with real data -->
                        <td>David Roy</td>
                        <!-- TODO: replace with real data -->
                        <td><span class="badge text-bg-warning">Pending</span></td>
                    </tr>
                    <tr>
                        <!-- TODO: replace with real data -->
                        <td>2026-04-24</td>
                        <!-- TODO: replace with real data -->
                        <td>Document PR-004 approved</td>
                        <!-- TODO: replace with real data -->
                        <td>Alex Martin</td>
                        <!-- TODO: replace with real data -->
                        <td><span class="badge text-bg-info">In Review</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
