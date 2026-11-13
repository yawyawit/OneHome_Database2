<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$b = get_booking((int) ($_GET['id'] ?? 0));
if (!$b || $b['customer_id'] != $u['id']) {
    flash('Booking not found.', 'danger');
    redirect('customer/bookings.php');
}
$review = db_one("SELECT * FROM reviews WHERE booking_id=?", [$b['id']]);
$title = 'Booking Details';
require INC . '/header.php'; ?>
<h2>Booking #<?= $b['id'] ?></h2>
<?php booking_info($b); ?>
<?php if ($review): ?>
    <div class="card mb-3">
        <div class="card-body"><b>Your review:</b> <?= stars($review['rating']) ?><br><?= e($review['comment']) ?></div>
    </div><?php endif; ?>
<?php if (in_array($b['status'], ['Pending', 'Confirmed'])): ?>
    <form method="post" action="<?= url('customer/cancel_booking.php') ?>" class="d-inline"
        data-confirm="Cancel this booking?"><input type="hidden" name="id" value="<?= $b['id'] ?>"><button
            class="btn btn-danger">Cancel Booking</button></form>
<?php endif; ?>
<?php if ($b['status'] === 'Completed' && $b['pstatus'] === 'Unpaid'): ?><a class="btn btn-success"
        href="<?= url('customer/payment.php?id=' . $b['id']) ?>">Pay Now</a><?php endif; ?>
<?php if ($b['status'] === 'Completed' && !$b['reviewed']): ?><a class="btn btn-warning"
        href="<?= url('customer/review.php?id=' . $b['id']) ?>">Rate &amp; Review</a><?php endif; ?>
<a class="btn btn-secondary" href="<?= url('customer/bookings.php') ?>">Back</a>
<?php require INC . '/footer.php'; ?>