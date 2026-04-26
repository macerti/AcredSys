<?php $title = 'Login'; require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>Login</h2>
    <form method="post" class="auth-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <label>Email
            <input type="email" name="email" required>
        </label>
        <label>Password
            <input type="password" name="password" required minlength="8">
        </label>
        <button type="submit">Login</button>
    </form>
</section>

<?php if (!empty($_SESSION['pending_login_org_ids'])): ?>
<section class="card">
    <h3>Select Organization</h3>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <input type="hidden" name="action" value="select-org">
        <label>Organization
            <select name="organization_id" required>
                <?php foreach ($_SESSION['pending_login_org_ids'] as $orgId): ?>
                    <option value="<?= (int) $orgId; ?>">Organization #<?= (int) $orgId; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Continue</button>
    </form>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>
