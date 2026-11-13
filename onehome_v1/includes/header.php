<?php
require_once __DIR__ . '/notifications.php';
$u = current_user();
$is_admin_layout = $u && $u['status'] === 'active' && $u['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <title><?= e($title ?? 'One Home') ?> | One Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= url('css/style.css') ?>" rel="stylesheet">
</head>

<body class="<?= $is_admin_layout ? 'admin-page' : 'public-page' ?>">
    <?php require __DIR__ . '/navbar.php'; ?>
    <?php if ($is_admin_layout): ?>
        <main class="page-content admin-page-content">
    <?php else: ?>
        <main class="container page-content py-4">
    <?php endif; ?>
        <?php show_flash(); ?>
