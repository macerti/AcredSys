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
<?php require __DIR__ . '/../layout/footer.php'; ?>
