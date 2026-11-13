<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Reports';
$svc = db_all("SELECT s.name, COUNT(b.id) c FROM services s LEFT JOIN bookings b ON b.service_id=s.id GROUP BY s.id ORDER BY c DESC LIMIT 5");
$emp = db_all(
    "SELECT u.name, COUNT(b.id) AS c
     FROM users u
     LEFT JOIN bookings b ON b.employee_id = u.id AND b.status = 'Completed'
     WHERE u.role = 'employee' AND u.status = 'active'
     GROUP BY u.id
     ORDER BY c DESC
     LIMIT 5"
);
$avg = db_val("SELECT ROUND(AVG(rating),2) FROM reviews");
$total = db_val("SELECT COUNT(*) FROM reviews");
function bars($rows)
{
    if (!$rows) {
        echo '<p class="small text-muted mb-0">No data available yet.</p>';
        return;
    }
    $max = max(1, max(array_column($rows, 'c') ?: [0]));
    foreach ($rows as $r) {
        echo '<div class="report-bar-row mb-3">'
            . '<div class="d-flex justify-content-between gap-3 mb-1">'
            . '<span>' . e($r['name']) . '</span>'
            . '<strong>' . number_format($r['c']) . '</strong>'
            . '</div>'
            . '<div class="progress"><div class="progress-bar" style="width:' . ($r['c'] / $max * 100) . '%"></div></div>'
            . '</div>';
    }
}
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">FINANCE &amp; INSIGHTS</p>
        <h1>Reports</h1>
        <p>Review service demand, completed work, and customer satisfaction.</p>
    </div>
    <div class="d-flex gap-2 no-print">
        <a class="btn btn-primary" href="monthly_report.php"><i class="bi bi-calendar3 me-1"></i>Monthly report</a>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Most requested services</div>
            <div class="card-body"><?php bars($svc); ?></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Employees with most completed jobs</div>
            <div class="card-body"><?php bars($emp); ?></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Customer satisfaction</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <strong class="fs-3"><?= $total ? e($avg) . ' / 5' : '—' ?></strong>
                    <span><?= $total ? stars($avg) : '<span class="text-muted small">No reviews yet</span>' ?></span>
                </div>
                <p class="small text-muted mb-3"><?= number_format($total) ?> customer review<?= $total === 1 ? '' : 's' ?> received</p>
                <?php for ($i = 5; $i >= 1; $i--):
                    $n = db_val("SELECT COUNT(*) FROM reviews WHERE rating=?", [$i]); ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="small text-muted" style="width:48px"><?= $i ?> star</span>
                        <div class="progress flex-grow-1"><div class="progress-bar bg-warning" style="width:<?= $total ? $n / $total * 100 : 0 ?>%"></div></div>
                        <span class="small text-muted ms-2" style="width:25px;text-align:right"><?= $n ?></span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
<?php require INC . '/footer.php'; ?>
