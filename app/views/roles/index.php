<?php $title = 'Role Management'; require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>Available Roles</h2>
    <ul>
        <?php foreach ($roles as $role): ?>
            <li><?= e($role['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</section>

<section class="card">
    <h2>Assign Roles to Users</h2>
    <?php foreach ($users as $user): ?>
        <form method="post" action="index.php?page=roles-update" class="role-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
            <input type="hidden" name="user_id" value="<?= e($user['id']); ?>">
            <strong><?= e($user['email']); ?></strong>
            <div class="checkbox-group">
                <?php foreach ($roles as $role): ?>
                    <?php $hasRole = in_array($role['name'], array_map('trim', explode(',', (string) ($user['roles'] ?? ''))), true); ?>
                    <label>
                        <input type="checkbox" name="role_ids[]" value="<?= (int) $role['id']; ?>" <?= $hasRole ? 'checked' : ''; ?>>
                        <?= e($role['name']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit">Update Roles</button>
        </form>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
