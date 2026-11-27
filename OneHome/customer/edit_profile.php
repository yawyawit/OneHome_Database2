<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'Edit Profile';
require INC . '/header.php'; ?>
<h2>Profile &amp; Settings</h2>
<?php account_forms($u); ?>
<a href="<?= url('customer/profile.php') ?>">&laquo; Back to profile</a>
<?php require INC . '/footer.php'; ?>