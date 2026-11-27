<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Workspace';
$id = $u['id'];
$new = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Pending'", [$id]);
$upcoming = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status IN ('Confirmed','Ongoing')", [$id]);
$done = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Completed'", [$id]);
$earn = db_val("SELECT COALESCE(SUM(p.amount),0) FROM payments p JOIN bookings b ON b.id=p.booking_id WHERE b.employee_id=? AND p.status='Paid'", [$id]);
$next = list_bookings("b.employee_id=? AND b.status IN ('Confirmed','Ongoing')", [$id], 'b.booking_date, b.booking_time');
$requests = list_bookings("b.employee_id=? AND b.status='Pending'", [$id], 'b.booking_date, b.booking_time');
require INC . '/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div><h2>Your workday, <?= e(explode(' ', $u['name'])[0]) ?>.</h2><div class="page-subtitle mb-0">Here is what needs your attention.</div></div>
    <a class="btn btn-primary" href="<?= url('employee/availability.php') ?>"><i class="bi bi-calendar-week me-1"></i> Availability</a>
</div>
<div class="row">
    <?= stat_card('New requests', $new, 'bi-inbox') ?>
    <?= stat_card('Upcoming jobs', $upcoming, 'bi-calendar-event') ?>
    <?= stat_card('Completed', $done, 'bi-check-circle') ?>
    <?= stat_card('Paid earnings', money($earn), 'bi-wallet2') ?>
</div>
<?php if ($new): ?>
    <a class="quick-link my-2" href="<?= url('employee/booking_requests.php') ?>"><i class="bi bi-bell"></i><span><?= $new ?> new request(s) need your response</span><i class="bi bi-arrow-right ms-auto"></i></a>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center mt-4 mb-2"><h4 class="mb-0">Next jobs</h4><a class="small text-decoration-none" href="<?= url('employee/my_jobs.php') ?>">All jobs</a></div>
<?php bookings_table(array_slice($next, 0, 5), 'employee'); ?>
<?php require INC . '/footer.php'; ?>
