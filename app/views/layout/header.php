<?php $flashMessages = get_flash(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Auth System'); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="topbar">
    <h1>Auth Testing App</h1>
    <nav>
        <?php if (is_logged_in()): ?>
            <a href="index.php?page=dashboard">Dashboard</a>
            <a href="index.php?page=profile">Profile</a>
            <a href="index.php?page=roles">Roles</a>
            <a href="index.php?page=logout">Logout</a>
        <?php else: ?>
            <a href="index.php?page=login">Login</a>
            <a href="index.php?page=register">Register</a>
            <a href="index.php?page=forgot-password">Forgot Password</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
    <?php foreach ($flashMessages as $flash): ?>
        <div class="alert <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
    <?php endforeach; ?>
