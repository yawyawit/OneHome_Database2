<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'My Bookings';
$statuses = ['Pending', 'Confirmed', 'Ongoing', 'Completed', 'Cancelled', 'Declined'];
$st = in_array($_GET['status'] ?? '', $statuses) ? $_GET['status'] : '';
$rows = $st ? list_bookings('b.customer_id=? AND b.status=?', [$u['id'], $st]) : list_bookings('b.customer_id=?', [$u['id']]);
require INC . '/header.php'; ?>
<h2>My Bookings</h2>
<div class="mb-3">
    <a class="btn btn-sm <?= $st ? 'btn-outline-dark' : 'btn-dark' ?>" href="bookings.php">All</a>
    <?php foreach ($statuses as $s): ?><a class="btn btn-sm <?= $st === $s ? 'btn-dark' : 'btn-outline-dark' ?>"
            href="bookings.php?status=<?= $s ?>"><?= $s ?></a> <?php endforeach; ?>
</div>
<?php bookings_table($rows, 'customer'); ?>
<?php require INC . '/footer.php'; ?>