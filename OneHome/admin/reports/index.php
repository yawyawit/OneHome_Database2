<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Reports';
$svc = db_all("SELECT s.name, COUNT(b.id) c FROM services s LEFT JOIN bookings b ON b.service_id=s.id GROUP BY s.id ORDER BY c DESC LIMIT 5");
$emp = db_all("SELECT u.name, COUNT(b.id) c FROM users u LEFT JOIN bookings b ON b.employee_id=u.id AND b.status='Completed' WHERE u.role='employee' AND u.status='active' GROUP BY u.id ORDER BY c DESC LIMIT 5");
$avg = db_val("SELECT ROUND(AVG(rating),2) FROM reviews");
$total = db_val("SELECT COUNT(*) FROM reviews");
function bars($rows)
{
    $max = max(1, max(array_column($rows, 'c') ?: [0]));
    foreach ($rows as $r)
        echo '<div class="mb-2"><div class="d-flex justify-content-between"><span>' . e($r['name']) . '</span><b>' . $r['c'] . '</b></div><div class="progress"><div class="progress-bar" style="width:' . ($r['c'] / $max * 100) . '%"></div></div></div>';
}
require INC . '/header.php'; ?>
<div class="d-flex justify-content-between">
    <h2>Reports</h2>
    <div class="no-print"><a class="btn btn-primary" href="monthly_report.php">Monthly Bookings &amp; Earnings</a>
        <button class="btn btn-outline-secondary" onclick="window.print()">Print</button></div>
</div>
<div class="row mt-3">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header">Most Requested Services</div>
            <div class="card-body"><?php bars($svc); ?></div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header">Employees with Most Completed Jobs</div>
            <div class="card-body"><?php bars($emp); ?></div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Customer Satisfaction</div>
            <div class="card-body">
                <p class="fs-3 mb-2"><?= $total ? stars($avg) . ' ' . $avg . ' / 5' : 'No ratings yet' ?> <small
                        class="text-muted fs-6">(<?= $total ?> reviews)</small></p>
                      <?php for ($i = 5; $i >= 1; $i--):
                          $n = db_val("SELECT COUNT(*) FROM reviews WHERE rating=?", [$i]); ?>
                    <div class="d-flex align-items-center mb-1"><span style="width:50px"><?= $i ?> ★</span>
                        <div class="progress flex-grow-1">
                            <div class="progress-bar bg-warning" style="width:<?= $total ? $n / $total * 100 : 0 ?>%"></div>
                        </div><span class="ms-2"><?= $n ?></span>
                    </div>
                      <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
<?php require INC . '/footer.php'; ?>