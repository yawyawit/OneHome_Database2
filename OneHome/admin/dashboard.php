<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');
$u = current_user();
$title = 'Overview';
$customers = db_val("SELECT COUNT(*) FROM users WHERE role='customer'");
$employees = db_val("SELECT COUNT(*) FROM users WHERE role='employee' AND status='active'");
$pendingEmployees = pending_employees_count();
$pendingBookings = db_val("SELECT COUNT(*) FROM bookings WHERE status='Pending'");
$completed = db_val("SELECT COUNT(*) FROM bookings WHERE status='Completed'");
$paid = db_val("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='Paid'");
$recent = array_slice(list_bookings(), 0, 5);
require INC . '/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div><h2>Overview</h2><div class="page-subtitle mb-0">Welcome back, <?= e($u['name']) ?>.</div></div>
    <a class="btn btn-primary" href="<?= url('admin/bookings/index.php') ?>"><i class="bi bi-calendar-check me-1"></i> View bookings</a>
</div>
<div class="row">
    <?= stat_card('Customers', $customers, 'bi-people') ?>
    <?= stat_card('Active team', $employees, 'bi-person-workspace') ?>
    <?= stat_card('Pending bookings', $pendingBookings, 'bi-hourglass') ?>
    <?= stat_card('Payments received', money($paid), 'bi-wallet2') ?>
</div>
<div class="row g-3 mt-1 mb-4">
    <?php if ($pendingEmployees): ?>
        <div class="col-md-6"><a class="quick-link" href="<?= url('admin/users/index.php?role=employee') ?>"><i class="bi bi-person-check"></i><span><?= $pendingEmployees ?> employee application(s) to review</span><i class="bi bi-arrow-right ms-auto"></i></a></div>
    <?php endif; ?>
    <?php if ($pendingBookings): ?>
        <div class="col-md-6"><a class="quick-link" href="<?= url('admin/bookings/index.php?status=Pending') ?>"><i class="bi bi-calendar2-week"></i><span><?= $pendingBookings ?> booking(s) waiting</span><i class="bi bi-arrow-right ms-auto"></i></a></div>
    <?php endif; ?>
    <?php if (!$pendingEmployees && !$pendingBookings): ?>
        <div class="col-12"><div class="quick-link"><i class="bi bi-check-circle"></i><span>Everything is up to date</span></div></div>
    <?php endif; ?>
</div>
<div class="d-flex justify-content-between align-items-center mb-2"><h4 class="mb-0">Recent bookings</h4><a href="<?= url('admin/bookings/index.php') ?>" class="small text-decoration-none">See all</a></div>
<?php bookings_table($recent, 'admin'); ?>
<?php require INC . '/footer.php'; ?>
