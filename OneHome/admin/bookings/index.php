<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Manage Bookings';
$statuses = ['Pending', 'Confirmed', 'Ongoing', 'Completed', 'Cancelled', 'Declined'];
$st = in_array($_GET['status'] ?? '', $statuses) ? $_GET['status'] : '';
$rows = $st ? list_bookings('b.status=?', [$st]) : list_bookings();
require INC . '/header.php'; ?>
<h2>All Bookings</h2>
<div class="mb-3"><a class="btn btn-sm <?= $st ? 'btn-outline-dark' : 'btn-dark' ?>" href="index.php">All
        (<?= db_val("SELECT COUNT(*) FROM bookings") ?>)</a>
    <?php foreach ($statuses as $s): ?><a class="btn btn-sm <?= $st === $s ? 'btn-dark' : 'btn-outline-dark' ?>"
            href="index.php?status=<?= $s ?>"><?= $s ?>
            (<?= db_val("SELECT COUNT(*) FROM bookings WHERE status=?", [$s]) ?>)</a> <?php endforeach; ?></div>
<?php bookings_table($rows, 'admin'); ?>
<?php require INC . '/footer.php'; ?>