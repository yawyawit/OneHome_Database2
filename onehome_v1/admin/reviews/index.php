<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Reviews';
$perf = db_all(
    "SELECT u.name, COUNT(r.id) AS cnt, ROUND(AVG(r.rating), 1) AS avg_r
     FROM users u
     LEFT JOIN reviews r ON r.employee_id = u.id
     WHERE u.role = 'employee' AND u.status = 'active'
     GROUP BY u.id
     ORDER BY avg_r DESC"
);
$rows = db_all(
    "SELECT r.*, c.name AS cname, e.name AS ename, s.name AS sname
     FROM reviews r
     JOIN bookings b ON b.id = r.booking_id
     JOIN users c ON c.id = r.customer_id
     JOIN users e ON e.id = r.employee_id
     JOIN services s ON s.id = b.service_id
     ORDER BY r.id DESC"
);
$review_count = count($rows);
$average_rating = $review_count ? db_val("SELECT ROUND(AVG(rating), 1) FROM reviews") : 0;
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">FEEDBACK</p>
        <h1>Reviews &amp; ratings</h1>
        <p>Read customer feedback and check service provider ratings.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <?= stat_card('Customer reviews', number_format($review_count), 'bi-chat-square-text', 'info') ?>
    <?= stat_card('Average rating', $review_count ? e($average_rating) . ' / 5' : '—', 'bi-star', 'warning') ?>
</div>

<section class="mb-4">
    <h3 class="mb-1">Employee performance</h3>
    <p class="small text-muted mb-3">Ratings are based on reviews submitted by customers.</p>
    <div class="card">
        <div class="table-responsive border-0">
            <?php if ($perf): ?>
                <table class="table table-hover">
                    <thead><tr><th>Employee</th><th>Reviews</th><th>Average rating</th></tr></thead>
                    <tbody>
                        <?php foreach ($perf as $p): ?>
                            <tr><td class="fw-semibold"><?= e($p['name']) ?></td><td><?= number_format($p['cnt']) ?></td><td><?= rating_text($p['avg_r'], $p['cnt']) ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state"><i class="bi bi-people"></i><strong>No employee ratings yet</strong></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section>
    <h3 class="mb-1">Recent customer feedback</h3>
    <p class="small text-muted mb-3">Latest reviews are listed first.</p>
    <div class="card">
        <div class="table-responsive border-0">
            <?php if ($rows): ?>
                <table class="table table-hover align-middle">
                    <thead><tr><th>Date</th><th>Customer</th><th>Employee</th><th>Service</th><th>Rating</th><th>Comment</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td class="text-nowrap"><?= fdate($r['created_at']) ?></td>
                                <td><?= e($r['cname']) ?></td>
                                <td><?= e($r['ename']) ?></td>
                                <td><?= e($r['sname']) ?></td>
                                <td class="text-nowrap"><?= stars($r['rating']) ?></td>
                                <td class="review-comment"><?= e($r['comment']) ?></td>
                                <td><a class="btn btn-sm btn-light" href="view.php?id=<?= $r['id'] ?>">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state"><i class="bi bi-chat-square-text"></i><strong>No reviews yet</strong><span>Customer reviews will appear here after completed services.</span></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require INC . '/footer.php'; ?>
