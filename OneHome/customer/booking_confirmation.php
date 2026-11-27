<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$b = get_booking((int) ($_GET['id'] ?? 0));
if (!$b || $b['customer_id'] != $u['id']) {
    flash('Booking not found.', 'danger');
    redirect('customer/bookings.php');
}
$title = 'Booking Confirmation';
require INC . '/header.php'; ?>
<div class="alert alert-success">
    <h4 class="mb-1"><i class="bi bi-check-circle"></i> Booking submitted!</h4>
    Your request was sent to <?= e($b['ename']) ?>. You will see the status change here once they accept or decline.
</div>
<?php booking_info($b); ?>
<a class="btn btn-primary" href="<?= url('customer/bookings.php') ?>">Go to My Bookings</a>
<a class="btn btn-outline-primary" href="<?= url('customer/dashboard.php') ?>">Back to Home</a>
<?php require INC . '/footer.php'; ?>