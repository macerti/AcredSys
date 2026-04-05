<?php $title = 'Dashboard'; require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>User Management</h2>
    <form method="post" action="index.php?page=user-create" class="grid-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <label>New User Email
            <input type="email" name="email" required>
        </label>
        <label>Temp Password
            <input type="password" name="password" required minlength="8">
        </label>
        <button type="submit">Create User</button>
    </form>
</section>

<section class="card">
    <h3>All Users</h3>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Email</th>
                <th>Active</th>
                <th>Verified</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= e($user['email']); ?></td>
                    <td><?= (int) $user['is_active'] ? 'Yes' : 'No'; ?></td>
                    <td><?= (int) $user['is_verified'] ? 'Yes' : 'No'; ?></td>
                    <td><?= e($user['roles'] ?: 'none'); ?></td>
                    <td>
                        <details>
                            <summary>Edit</summary>
                            <form method="post" action="index.php?page=user-update" class="inline-form">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                                <input type="hidden" name="id" value="<?= e($user['id']); ?>">
                                <input type="email" name="email" value="<?= e($user['email']); ?>" required>
                                <label><input type="checkbox" name="is_active" <?= (int) $user['is_active'] ? 'checked' : ''; ?>> Active</label>
                                <label><input type="checkbox" name="is_verified" <?= (int) $user['is_verified'] ? 'checked' : ''; ?>> Verified</label>
                                <button type="submit">Save</button>
                            </form>
                        </details>
                        <form method="post" action="index.php?page=user-delete" onsubmit="return confirm('Delete user?');">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                            <input type="hidden" name="id" value="<?= e($user['id']); ?>">
                            <button type="submit" class="danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
