<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Earnings';
$rows = list_bookings("b.employee_id=? AND b.status='Completed'", [$u['id']]);
$paid = $pending = 0;
foreach ($rows as $b) {
    if ($b['pstatus'] === 'Paid')
        $paid += $b['price'];
    elseif ($b['pstatus'] === 'Unpaid')
        $pending += $b['price'];
}
require INC . '/header.php'; ?>
<h2>Earnings</h2>
<div class="row mt-3">
    <?= stat_card('Total earned (paid)', money($paid), 'bi-cash-coin', 'success') ?><?= stat_card('Pending payments', money($pending), 'bi-hourglass-split', 'warning') ?><?= stat_card('Completed jobs', count($rows), 'bi-check-circle', 'primary') ?>
</div>
<a href="<?= url('employee/payment_history.php') ?>" class="btn btn-outline-primary mb-3">View payment history</a>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No completed jobs yet.</div><?php else: ?>
    <table class="table table-striped bg-white">
        <thead class="table-light">
            <tr>
                <th>Job</th>
                <th>Service</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $b): ?>
                <tr>
                    <td>#<?= $b['id'] ?></td>
                    <td><?= e($b['sname']) ?></td>
                    <td><?= e($b['cname']) ?></td>
                    <td><?= fdate($b['booking_date']) ?></td>
                    <td><?= money($b['price']) ?></td>
                    <td><?= badge($b['pstatus']) ?></td>
                </tr><?php endforeach; ?>
        </tbody>
    </table><?php endif; ?>
<p class="small text-muted">Pending payments are jobs the customer has not paid yet.</p>
<?php require INC . '/footer.php'; ?>