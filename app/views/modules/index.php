<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2><?= e($title); ?></h2>
    <p>Organization ID: <strong><?= (int) require_org_context(); ?></strong></p>
    <p>Pagination defaults: limit <?= (int) $limit; ?>, offset <?= (int) $offset; ?>.</p>
</section>

<section class="card">
    <h3>Records</h3>
    <?php if (empty($rows)): ?>
        <p>No records found for this organization.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <?php foreach (array_keys($rows[0]) as $column): ?>
                        <th><?= e((string) $column); ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?= e(is_scalar($value) ? (string) $value : json_encode($value)); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
