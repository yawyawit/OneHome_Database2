<?php
require_once __DIR__ . '/notifications.php';
$u = current_user();
$role_class = $u && $u['status'] === 'active' ? $u['role'] : 'guest';
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if ($u) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'One Home') ?> | One Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= url('css/style.css') ?>" rel="stylesheet">
</head>
<body class="role-<?= e($role_class) ?>">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container app-main">
        <?php show_flash(); ?>
