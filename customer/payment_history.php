<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'Payment History';
$rows = list_bookings('b.customer_id=? AND b.status<>"Declined"', [$u['id']]);
require INC . '/header.php'; ?>
<h2>Payment History</h2>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No payments yet.</div><?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped bg-white">
            <thead class="table-light">
                <tr>
                    <th>Booking</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Paid on</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $b): ?>
                    <tr>
                        <td>#<?= $b['id'] ?></td>
                        <td><?= e($b['sname']) ?></td>
                        <td><?= money($b['price']) ?></td>
                        <td><?= pay_label($b['pmethod']) ?></td>
                        <td><?= badge($b['pstatus']) ?></td>
                        <td><?= $b['paid_at'] ? fdate($b['paid_at']) : '-' ?></td>
                        <td><?php if ($b['status'] === 'Completed' && $b['pstatus'] === 'Unpaid'): ?><a
                                    class="btn btn-sm btn-success"
                                    href="<?= url('customer/payment.php?id=' . $b['id']) ?>">Pay</a><?php endif; ?></td>
                    </tr><?php endforeach; ?>
            </tbody>
        </table>
    </div><?php endif; ?>
<?php require INC . '/footer.php'; ?>