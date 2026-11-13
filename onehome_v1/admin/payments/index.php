<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Payments';
$st = in_array($_GET['status'] ?? '', ['Unpaid', 'Paid', 'Refunded'], true) ? $_GET['status'] : '';
$mt = in_array($_GET['method'] ?? '', ['cash', 'card'], true) ? $_GET['method'] : '';
$rows = db_all(
    "SELECT p.*, b.id AS bid, c.name AS cname, e.name AS ename,
            s.name AS sname, b.status AS bstatus
     FROM payments p
     JOIN bookings b ON b.id = p.booking_id
     JOIN users c ON c.id = b.customer_id
     JOIN users e ON e.id = b.employee_id
     JOIN services s ON s.id = b.service_id
     WHERE (? = '' OR p.status = ?)
     AND (? = '' OR p.method = ?)
     ORDER BY p.id DESC",
    [$st, $st, $mt, $mt]
);
$received = db_val("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='Paid'");
$unpaid = db_val("SELECT COALESCE(SUM(amount),0) FROM payments p JOIN bookings b ON b.id=p.booking_id WHERE p.status='Unpaid' AND b.status='Completed'");
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">FINANCE</p>
        <h1>Payments</h1>
        <p>Check recorded payments and follow up completed services that are still unpaid.</p>
    </div>
</div>

<div class="row g-3 mb-3">
    <?= stat_card('Total received', money($received), 'bi-wallet2', 'success') ?>
    <?= stat_card('Completed jobs unpaid', money($unpaid), 'bi-hourglass-split', 'warning') ?>
</div>

<form class="card card-body filter-bar mb-3" method="get">
    <div class="row g-2 align-items-end">
        <div class="col-sm-5 col-md-3">
            <label class="form-label" for="paymentStatus">Payment status</label>
            <select name="status" id="paymentStatus" class="form-select">
                <option value="">All statuses</option>
                <?php foreach (['Unpaid', 'Paid', 'Refunded'] as $status): ?><option value="<?= e($status) ?>" <?= $st === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-5 col-md-3">
            <label class="form-label" for="paymentMethod">Payment method</label>
            <select name="method" id="paymentMethod" class="form-select">
                <option value="">All methods</option>
                <option value="card" <?= $mt === 'card' ? 'selected' : '' ?>>Simulated online payment</option>
                <option value="cash" <?= $mt === 'cash' ? 'selected' : '' ?>>Cash after service</option>
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-primary">Apply filter</button></div>
        <?php if ($st || $mt): ?><div class="col-auto"><a class="btn btn-light" href="index.php">Clear</a></div><?php endif; ?>
        <div class="col text-sm-end"><span class="small text-muted"><?= count($rows) ?> result<?= count($rows) === 1 ? '' : 's' ?></span></div>
    </div>
</form>

<div class="card">
    <div class="card-header">Payment records</div>
    <div class="table-responsive border-0">
        <?php if ($rows): ?>
            <table class="table table-hover align-middle">
                <thead><tr><th>Payment</th><th>Booking</th><th>Customer</th><th>Amount</th><th>Method</th><th>Status</th><th>Paid on</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($rows as $p): ?>
                        <tr>
                            <td class="fw-semibold">#<?= (int) $p['id'] ?></td>
                            <td>#<?= (int) $p['bid'] ?><br><span class="small text-muted"><?= e($p['sname']) ?></span></td>
                            <td><?= e($p['cname']) ?></td>
                            <td class="fw-semibold text-nowrap"><?= money($p['amount']) ?></td>
                            <td><?= pay_label($p['method']) ?></td>
                            <td><?= badge($p['status']) ?></td>
                            <td><?= $p['paid_at'] ? fdate($p['paid_at']) : '<span class="text-muted">—</span>' ?></td>
                            <td><a class="btn btn-sm btn-light" href="view.php?id=<?= $p['id'] ?>">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-wallet2"></i><strong>No payment records found</strong><span>Try changing the selected filters.</span></div>
        <?php endif; ?>
    </div>
</div>
<?php require INC . '/footer.php'; ?>
