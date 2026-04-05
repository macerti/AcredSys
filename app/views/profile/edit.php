<?php $title = 'Edit Profile'; require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>My Profile</h2>
    <form method="post" class="grid-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <label>Display Name
            <input type="text" name="display_name" value="<?= e($profile['display_name']); ?>">
        </label>
        <label>Avatar URL
            <input type="url" name="avatar_url" value="<?= e($profile['avatar_url']); ?>">
        </label>
        <label>Bio
            <textarea name="bio" rows="4"><?= e($profile['bio']); ?></textarea>
        </label>
        <label>Public ID
            <input type="text" name="public_id" value="<?= e($profile['public_id']); ?>" required>
        </label>
        <button type="submit">Save Profile</button>
    </form>

    <?php if (!empty($profile['avatar_url'])): ?>
        <div class="avatar-preview">
            <p>Avatar preview:</p>
            <img src="<?= e($profile['avatar_url']); ?>" alt="Avatar preview" loading="lazy">
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
