<?php $title = 'Forgot Password'; require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>Forgot Password</h2>
    <p>Enter your email to generate a reset token (simulates sending an email).</p>
    <form method="post" class="auth-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <label>Email
            <input type="email" name="email" required>
        </label>
        <button type="submit">Generate Token</button>
    </form>
    <p><a href="index.php?page=reset-password">Already have a token? Reset password</a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
