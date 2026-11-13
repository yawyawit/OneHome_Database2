<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Employee Dashboard';
$id = $u['id'];
$new = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Pending'", [$id]);
$upcoming = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status IN ('Confirmed','Ongoing')", [$id]);
$done = db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Completed'", [$id]);
$earn = db_val("SELECT COALESCE(SUM(p.amount),0) FROM payments p JOIN bookings b ON b.id=p.booking_id WHERE b.employee_id=? AND p.status='Paid'", [$id]);
$avg = db_val("SELECT ROUND(AVG(rating),1) FROM reviews WHERE employee_id=?", [$id]);
$rc = db_val("SELECT COUNT(*) FROM reviews WHERE employee_id=?", [$id]);
$next = list_bookings("b.employee_id=? AND b.status IN ('Confirmed','Ongoing')", [$id], 'b.booking_date, b.booking_time');
require INC . '/header.php'; ?>
<h2>Hello, <?= e($u['name']) ?>!</h2>
<div class="row mt-3">
    <?= stat_card('New booking requests', $new, 'bi-inbox', 'danger') ?>
    <?= stat_card('Upcoming jobs', $upcoming, 'bi-calendar-event', 'primary') ?>
    <?= stat_card('Completed jobs', $done, 'bi-check-circle', 'success') ?>
    <?= stat_card('Total earnings', money($earn), 'bi-cash-coin', 'success') ?>
    <?= stat_card('Average rating', $rc ? stars($avg) . ' ' . $avg : 'No ratings yet', 'bi-star', 'warning') ?>
</div>
<?php if ($new): ?>
    <div class="alert alert-warning">You have <b><?= $new ?></b> new request(s). <a
            href="<?= url('employee/booking_requests.php') ?>">Review them now</a></div><?php endif; ?>
<h4 class="mt-3">Next Jobs</h4>
<?php bookings_table(array_slice($next, 0, 5), 'employee'); ?>
<?php require INC . '/footer.php'; ?>