<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$b = get_booking((int) ($_GET['id'] ?? 0));
if (!$b || $b['customer_id'] != $u['id'] || $b['pstatus'] !== 'Paid') {
    redirect('customer/bookings.php');
}
$title = 'Payment Successful';
require INC . '/header.php'; ?>
<div class="card text-center">
    <div class="card-body p-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size:4rem"></i>
        <h2>Payment Successful!</h2>
        <p>You paid <b><?= money($b['price']) ?></b> for <?= e($b['sname']) ?> (card ending <?= e($b['card_last4']) ?>).
        </p>
        <a class="btn btn-warning" href="<?= url('customer/review.php?id=' . $b['id']) ?>">Rate &amp; Review
            <?= e($b['ename']) ?></a>
        <a class="btn btn-outline-primary" href="<?= url('customer/payment_history.php') ?>">Payment History</a>
    </div>
</div>
<?php require INC . '/footer.php'; ?>