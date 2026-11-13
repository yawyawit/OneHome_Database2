<?php require_once __DIR__ . '/../includes/auth.php';
require_once INC . '/notifications.php';
require_role('admin');
$u = current_user();
$title = 'Admin Dashboard';
$cust = db_val("SELECT COUNT(*) FROM users WHERE role='customer'");
$emp = db_val("SELECT COUNT(*) FROM users WHERE role='employee' AND status='active'");
$pendEmp = pending_employees_count();
$total = db_val("SELECT COUNT(*) FROM bookings");
$done = db_val("SELECT COUNT(*) FROM bookings WHERE status='Completed'");
$pend = db_val("SELECT COUNT(*) FROM bookings WHERE status='Pending'");
$paid = db_val("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='Paid'");
$recent = array_slice(list_bookings(), 0, 5);
require INC . '/header.php'; ?>
<h2>Admin Dashboard</h2>
<div class="row mt-3">
    <?= stat_card('Total customers', $cust, 'bi-people', 'primary') ?>
    <?= stat_card('Active employees', $emp, 'bi-person-workspace', 'primary') ?>
    <?= stat_card('Total bookings', $total, 'bi-calendar-check', 'info') ?>
    <?= stat_card('Completed bookings', $done, 'bi-check-circle', 'success') ?>
    <?= stat_card('Pending bookings', $pend, 'bi-hourglass-split', 'warning') ?>
    <?= stat_card('Payments received', money($paid), 'bi-cash-coin', 'success') ?>
</div>
<?php if ($pendEmp): ?>
    <div class="alert alert-warning"><?= $pendEmp ?> employee account(s) waiting for approval.
        <a href="<?= url('admin/users/index.php?role=employee') ?>">Review</a>
    </div>
<?php endif; ?>
<h4>Recent Bookings</h4>
<?php bookings_table($recent, 'admin'); ?>
<?php require INC . '/footer.php'; ?>