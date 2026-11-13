<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'My Jobs';
$past = ($_GET['tab'] ?? '') === 'past';
$rows = $past ? list_bookings("b.employee_id=? AND b.status IN ('Completed','Cancelled','Declined')", [$u['id']])
    : list_bookings("b.employee_id=? AND b.status IN ('Pending','Confirmed','Ongoing')", [$u['id']], 'b.booking_date, b.booking_time');
require INC . '/header.php'; ?>
<h2>My Jobs</h2>
<div class="mb-3"><a class="btn btn-sm <?= $past ? 'btn-outline-dark' : 'btn-dark' ?>" href="my_jobs.php">Current
        Jobs</a> <a class="btn btn-sm <?= $past ? 'btn-dark' : 'btn-outline-dark' ?>" href="my_jobs.php?tab=past">Past
        Jobs</a></div>
<?php bookings_table($rows, 'employee'); ?>
<?php require INC . '/footer.php'; ?>