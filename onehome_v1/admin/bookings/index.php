<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Bookings';
$statuses = ['Pending', 'Confirmed', 'Ongoing', 'Completed', 'Cancelled', 'Declined'];
$st = in_array($_GET['status'] ?? '', $statuses, true) ? $_GET['status'] : '';
$rows = $st ? list_bookings('b.status=?', [$st]) : list_bookings();
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">OPERATIONS</p>
        <h1>Bookings</h1>
        <p>Review service requests and track each booking from request to completion.</p>
    </div>
</div>

<form class="card card-body filter-bar mb-3" method="get">
    <div class="row g-2 align-items-end">
        <div class="col-sm-6 col-md-4">
            <label class="form-label" for="bookingStatus">Filter by status</label>
            <select class="form-select" name="status" id="bookingStatus">
                <option value="">All bookings</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= e($status) ?>" <?= $st === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-primary">Apply filter</button></div>
        <?php if ($st): ?><div class="col-auto"><a class="btn btn-light" href="index.php">Clear</a></div><?php endif; ?>
        <div class="col text-sm-end"><span class="small text-muted"><?= count($rows) ?> result<?= count($rows) === 1 ? '' : 's' ?></span></div>
    </div>
</form>
<div class="card">
    <div class="card-header">Booking records</div>
    <div class="card-body p-0">
        <?php bookings_table($rows, 'admin'); ?>
    </div>
</div>
<?php require INC . '/footer.php'; ?>
