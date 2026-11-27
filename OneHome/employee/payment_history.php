<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Payment History';
$rows = list_bookings("b.employee_id=? AND p.status IN ('Paid','Refunded')", [$u['id']], 'p.paid_at DESC');
require INC . '/header.php'; ?>
<h2>Payment History</h2>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No payments received yet.</div><?php else: ?>
    <table class="table table-striped bg-white">
        <thead class="table-light">
            <tr>
                <th>Job</th>
                <th>Service</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Paid on</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $b): ?>
                <tr>
                    <td>#<?= $b['id'] ?></td>
                    <td><?= e($b['sname']) ?></td>
                    <td><?= e($b['cname']) ?></td>
                    <td><?= money($b['price']) ?></td>
                    <td><?= pay_label($b['pmethod']) ?></td>
                    <td><?= badge($b['pstatus']) ?></td>
                    <td><?= $b['paid_at'] ? fdate($b['paid_at']) : '-' ?></td>
                </tr><?php endforeach; ?>
        </tbody>
    </table><?php endif; ?>
<a href="<?= url('employee/earnings.php') ?>">&laquo; Back to earnings</a>
<?php require INC . '/footer.php'; ?>