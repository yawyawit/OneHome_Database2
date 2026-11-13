<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Monthly Report';
$rows = db_all("SELECT DATE_FORMAT(b.booking_date,'%Y-%m') m, COUNT(*) total, SUM(b.status='Completed') done, SUM(b.status IN ('Cancelled','Declined')) cancelled,
  COALESCE(SUM(CASE WHEN p.status='Paid' THEN p.amount END),0) earnings
  FROM bookings b LEFT JOIN payments p ON p.booking_id=b.id GROUP BY m ORDER BY m DESC LIMIT 12");
require INC . '/header.php'; ?>
<div class="d-flex justify-content-between">
    <h2>Monthly Bookings &amp; Earnings</h2><button class="btn btn-outline-secondary no-print"
        onclick="window.print()">Print</button>
</div>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No data yet.</div><?php else: ?>
    <table class="table table-striped bg-white">
        <thead class="table-light">
            <tr>
                <th>Month</th>
                <th>Total bookings</th>
                <th>Completed</th>
                <th>Cancelled/Declined</th>
                <th>Earnings (paid)</th>
            </tr>
        </thead>
        <tbody>
                  <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= date('F Y', strtotime($r['m'] . '-01')) ?></td>
                    <td><?= $r['total'] ?></td>
                    <td><?= $r['done'] ?></td>
                    <td><?= $r['cancelled'] ?></td>
                    <td><?= money($r['earnings']) ?></td>
                </tr><?php endforeach; ?>
        </tbody>
    </table><?php endif; ?>
<a class="btn btn-secondary no-print" href="index.php">Back to reports</a>
<?php require INC . '/footer.php'; ?>