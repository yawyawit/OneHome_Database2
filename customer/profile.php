<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'My Profile';
$total = db_val("SELECT COUNT(*) FROM bookings WHERE customer_id=?", [$u['id']]);
require INC . '/header.php'; ?>
<h2>My Profile</h2>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <p><b>Name:</b> <?= e($u['name']) ?></p>
        <p><b>Email:</b> <?= e($u['email']) ?></p>
        <p><b>Contact number:</b> <?= e($u['phone']) ?></p>
        <p><b>Home address:</b> <?= e($u['address'] ?: 'Not set') ?></p>
        <p><b>Total bookings:</b> <?= $total ?></p>
        <a class="btn btn-primary" href="<?= url('customer/edit_profile.php') ?>">Edit Profile &amp; Settings</a>
        <a class="btn btn-outline-danger" href="<?= url('logout.php') ?>">Log out</a>
    </div>
</div>
<?php require INC . '/footer.php'; ?>