<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'My Reviews';
$rows = db_all(
    "SELECT r.*, c.name AS cname, s.name AS sname
     FROM reviews r
     JOIN bookings b ON b.id = r.booking_id
     JOIN users c ON c.id = r.customer_id
     JOIN services s ON s.id = b.service_id
     WHERE r.employee_id = ?
     ORDER BY r.id DESC",
    [$u['id']]
);
$avg = db_val("SELECT ROUND(AVG(rating),1) FROM reviews WHERE employee_id=?", [$u['id']]);
$low = 0;
foreach ($rows as $r)
    if ($r['rating'] <= 3)
        $low++;
require INC . '/header.php'; ?>
<h2>My Reviews &amp; Ratings</h2>
<div class="row mt-3">
    <?= stat_card('Average rating', $rows ? stars($avg) . ' ' . $avg : 'No ratings yet', 'bi-star', 'warning') ?>
    <?= stat_card('Total reviews', count($rows), 'bi-chat-quote', 'primary') ?>
</div>
<?php if ($low): ?>
    <div class="alert alert-info">Tip: <?= $low ?> review(s) have 3 stars or below (highlighted). Read the comments to see
        what you can improve.</div><?php endif; ?>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No reviews yet.</div><?php endif; ?>
<?php foreach ($rows as $r): ?>
    <div class="card mb-2 <?= $r['rating'] <= 3 ? 'border-warning' : '' ?>">
        <div class="card-body"><b><?= e($r['cname']) ?></b> &mdash; <?= e($r['sname']) ?>     <?= stars($r['rating']) ?>
            <div><?= e($r['comment'] ?: '(no comment)') ?></div><small
                class="text-muted"><?= fdate($r['created_at']) ?></small>
        </div>
    </div>
<?php endforeach; ?>
<?php require INC . '/footer.php'; ?>