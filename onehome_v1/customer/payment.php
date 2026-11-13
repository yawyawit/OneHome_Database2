<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$b = get_booking((int) ($_GET['id'] ?? 0));
if (!$b || $b['customer_id'] != $u['id']) {
    flash('Booking not found.', 'danger');
    redirect('customer/bookings.php');
}
if ($b['status'] !== 'Completed') {
    flash('You can pay once the job is completed.', 'warning');
    redirect('customer/booking_details.php?id=' . $b['id']);
}
$method = in_array($_GET['method'] ?? '', ['cash', 'card']) ? $_GET['method'] : $b['pmethod'];
$title = 'Payment';
require INC . '/header.php'; ?>
<h2>Payment</h2>
<div class="row">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header">Payment Summary</div>
            <div class="card-body">
                <p><b>Service:</b> <?= e($b['sname']) ?> (Booking #<?= $b['id'] ?>)</p>
                <p><b>Employee:</b> <?= e($b['ename']) ?></p>
                <p class="fs-4"><b>Total:</b> <?= money($b['price']) ?></p>
                <p class="mb-0"><b>Status:</b> <?= badge($b['pstatus']) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-7">
            <?php if ($b['pstatus'] === 'Paid'): ?>
            <div class="alert alert-success">This booking is already paid. Thank you!</div>
            <?php else: ?>
            <div class="mb-3"><a class="btn btn-sm <?= $method === 'cash' ? 'btn-dark' : 'btn-outline-dark' ?>"
                    href="payment.php?id=<?= $b['id'] ?>&method=cash">Pay after service (cash)</a>
                <a class="btn btn-sm <?= $method === 'card' ? 'btn-dark' : 'btn-outline-dark' ?>"
                    href="payment.php?id=<?= $b['id'] ?>&method=card">Pay by card (simulated)</a>
            </div>
                <?php if ($method === 'cash'): ?>
                <div class="card card-body">
                    <p>Pay <b><?= money($b['price']) ?></b> in cash to <?= e($b['ename']) ?>. They will mark the job as paid
                        once they
                        receive it.</p>
                    <form method="post" action="<?= url('actions/process_payment.php') ?>"><input type="hidden"
                            name="booking_id" value="<?= $b['id'] ?>"><input type="hidden" name="action" value="cash"><button
                            class="btn btn-primary">Choose
                            Pay After Service</button></form>
                </div>
                <?php else: ?>
                <form method="post" action="<?= url('actions/process_payment.php') ?>" class="card card-body">
                    <div class="alert alert-info small">DEMO ONLY &mdash; no real money is charged. Use any 16 digits, e.g. 4242
                        4242
                        4242 4242.</div>
                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>"><input type="hidden" name="action"
                        value="card">
                    <label class="form-label">Name on card</label><input name="card_name" class="form-control mb-2" required>
                    <label class="form-label">Card number</label><input name="card_number" id="card_number"
                        class="form-control mb-2" placeholder="1234 5678 9012 3456" required>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Expiry (MM/YY)</label><input name="expiry"
                                class="form-control" placeholder="12/28" required></div>
                        <div class="col-6 mb-3"><label class="form-label">CVV</label><input name="cvv" class="form-control"
                                maxlength="4" required></div>
                    </div>
                    <button class="btn btn-success">Pay <?= money($b['price']) ?></button>
                </form>
                <?php endif; ?>
            <?php endif; ?>
    </div>
</div>
<?php require INC . '/footer.php'; ?>