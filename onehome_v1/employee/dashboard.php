<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Overview';
$id = $u['id'];
$new = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Pending'", [$id]);
$upcoming = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status IN ('Confirmed','Ongoing')", [$id]);
$done = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Completed'", [$id]);
$earn = db_val("SELECT COALESCE(SUM(p.amount),0) FROM payments p JOIN bookings b ON b.id=p.booking_id WHERE b.employee_id=? AND p.status='Paid'", [$id]);
$avg = db_val("SELECT ROUND(AVG(rating),1) FROM reviews WHERE employee_id=?", [$id]);
$rc = db_val("SELECT COUNT(*) FROM reviews WHERE employee_id=?", [$id]);
$next = list_bookings("b.employee_id=? AND b.status IN ('Confirmed','Ongoing')", [$id], 'b.booking_date, b.booking_time');
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">SERVICE PROVIDER WORKSPACE</p>
        <h1>Hello, <?= e($u['name']) ?></h1>
        <p>Keep track of your requests, scheduled jobs, and completed work.</p>
    </div>
    <?php if ($rc): ?>
        <div class="rating-summary"><i class="bi bi-star-fill"></i><strong><?= e($avg) ?> / 5</strong><span><?= number_format($rc) ?> review<?= $rc === 1 ? '' : 's' ?></span></div>
    <?php endif; ?>
</div>

<div class="row g-3 mb-4">
    <?= stat_card('New requests', number_format($new), 'bi-inbox', 'warning') ?>
    <?= stat_card('Upcoming jobs', number_format($upcoming), 'bi-calendar-event', 'primary') ?>
    <?= stat_card('Completed jobs', number_format($done), 'bi-check-circle', 'success') ?>
    <?= stat_card('Total earnings', money($earn), 'bi-wallet2', 'info') ?>
</div>

<?php if ($new): ?>
    <div class="alert alert-warning d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="bi bi-bell me-2"></i>You have <strong><?= number_format($new) ?> new request<?= $new === 1 ? '' : 's' ?></strong> waiting for your response.</span>
        <a class="btn btn-sm btn-warning" href="<?= url('employee/booking_requests.php') ?>">Review requests</a>
    </div>
<?php endif; ?>

<div class="d-flex align-items-center justify-content-between gap-3 mb-3">
    <div><h3 class="mb-1">Your next jobs</h3><p class="small text-muted mb-0">Confirmed and ongoing work, ordered by schedule.</p></div>
    <a class="btn btn-sm btn-outline-primary" href="<?= url('employee/my_jobs.php') ?>">View all jobs</a>
</div>
<?php bookings_table(array_slice($next, 0, 5), 'employee'); ?>
<?php require INC . '/footer.php'; ?>
