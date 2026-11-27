<?php require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$u = current_user();
$title = 'Admin Profile';
require INC . '/header.php'; ?>
<h2>Admin Profile</h2>
<?php account_forms($u); ?>
<?php require INC . '/footer.php'; ?>