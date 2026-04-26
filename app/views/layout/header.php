<?php
$flashMessages = get_flash();
$title = $title ?? 'AcredSys';
$isAuthPage = in_array($_GET['page'] ?? 'login', ['login', 'register', 'forgot-password', 'reset-password'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="topbar">
    <h1>AcredSys</h1>
    <?php if (is_logged_in()): ?>
        <p>User: <?= e($_SESSION['user_name'] ?? ''); ?> | Org: <?= (int) ($_SESSION['organization_id'] ?? 0); ?></p>
    <?php endif; ?>
</header>
<main class="container">
    <?php foreach ($flashMessages as $flash): ?>
        <div class="alert <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
    <?php endforeach; ?>

    <?php if (!$isAuthPage && is_logged_in()): ?>
        <?php require __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>
