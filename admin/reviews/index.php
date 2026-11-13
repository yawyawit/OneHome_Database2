<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Reviews & Ratings';
$perf = db_all("SELECT u.name, COUNT(r.id) cnt, ROUND(AVG(r.rating),1) avg_r FROM users u LEFT JOIN reviews r ON r.employee_id=u.id WHERE u.role='employee' AND u.status='active' GROUP BY u.id ORDER BY avg_r DESC");
$rows = db_all("SELECT r.*, c.name cname, e.name ename, s.name sname FROM reviews r JOIN bookings b ON b.id=r.booking_id JOIN users c ON c.id=r.customer_id JOIN users e ON e.id=r.employee_id JOIN services s ON s.id=b.service_id ORDER BY r.id DESC");
require INC . '/header.php'; ?>
<h2>Reviews &amp; Ratings</h2>
<h5 class="mt-3">Employee Performance</h5>
<table class="table table-sm bg-white" style="max-width:600px">
    <thead class="table-light">
        <tr>
            <th>Employee</th>
            <th>Reviews</th>
            <th>Average rating</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($perf as $p): ?>
            <tr>
                <td><?= e($p['name']) ?></td>
                <td><?= $p['cnt'] ?></td>
                <td><?= rating_text($p['avg_r'], $p['cnt']) ?></td>
            </tr><?php endforeach; ?>
    </tbody>
</table>
<h5 class="mt-4">All Reviews</h5>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No reviews yet.</div><?php else: ?>
    <table class="table table-striped bg-white">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Employee</th>
                <th>Service</th>
                <th>Rating</th>
                <th>Comment</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr class="<?= $r['rating'] <= 2 ? 'table-danger' : '' ?>">
                    <td><?= fdate($r['created_at']) ?></td>
                    <td><?= e($r['cname']) ?></td>
                    <td><?= e($r['ename']) ?></td>
                    <td><?= e($r['sname']) ?></td>
                    <td><?= stars($r['rating']) ?></td>
                    <td><?= e($r['comment']) ?></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="view.php?id=<?= $r['id'] ?>">View</a></td>
                </tr><?php endforeach; ?>
        </tbody>
    </table><?php endif; ?>
<?php require INC . '/footer.php'; ?>