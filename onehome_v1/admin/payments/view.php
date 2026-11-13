<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$p = db_one("SELECT * FROM payments WHERE id=?", [(int) ($_GET['id'] ?? $_POST['id'] ?? 0)]);
if (!$p) {
    flash('Payment not found.', 'danger');
    redirect('admin/payments/index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'paid' && $p['status'] === 'Unpaid')
        db_exec("UPDATE payments SET status='Paid', paid_at=NOW() WHERE id=?", [$p['id']]);
    if ($_POST['action'] === 'refund' && $p['status'] === 'Paid')
        db_exec("UPDATE payments SET status='Refunded' WHERE id=?", [$p['id']]);
    flash('Payment updated.');
    redirect('admin/payments/view.php?id=' . $p['id']);
}
$b = get_booking($p['booking_id']);
$title = 'Payment Details';
require INC . '/header.php'; ?>
<h2>Payment #<?= $p['id'] ?> <?= badge($p['status']) ?></h2>
<?php booking_info($b); ?>
<?php if ($p['card_last4']): ?>
    <p>Card ending in <b><?= e($p['card_last4']) ?></b></p><?php endif; ?>
<?php if ($p['status'] === 'Unpaid'): ?>
    <form method="post" class="d-inline" data-confirm="Mark as paid?"><input type="hidden" name="id"
            value="<?= $p['id'] ?>"><input type="hidden" name="action" value="paid"><button class="btn btn-success">Mark as
            Paid</button></form><?php endif; ?>
<?php if ($p['status'] === 'Paid'): ?>
    <form method="post" class="d-inline" data-confirm="Refund this payment?"><input type="hidden" name="id"
            value="<?= $p['id'] ?>"><input type="hidden" name="action" value="refund"><button
            class="btn btn-danger">Refund</button></form><?php endif; ?>
<a class="btn btn-secondary" href="index.php">Back</a>
<?php require INC . '/footer.php'; ?>