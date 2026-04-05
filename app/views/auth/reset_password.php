<?php $title = 'Reset Password'; require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>Reset Your Password</h2>
    
    <?php if (!empty($reset)): ?>
        <!-- Token is valid, only show password form -->
        <form method="post" class="auth-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <input type="hidden" name="token" value="<?= e($token); ?>">
            
            <p style="color: #666; margin-bottom: 20px;">Enter your new password to reset your account.</p>
            
            <label>New Password
                <input type="password" name="password" required minlength="8" placeholder="At least 8 characters" autofocus>
            </label>
            
            <label>Confirm Password
                <input type="password" name="confirm_password" required minlength="8" placeholder="Confirm password">
            </label>
            
            <button type="submit">Reset Password</button>
        </form>
    <?php else: ?>
        <!-- No valid token in URL -->
        <p style="color: #d9534f; text-align: center;">
            Invalid or expired reset link.<br>
            <a href="index.php?page=forgot-password">Request a new password reset</a>
        </p>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
