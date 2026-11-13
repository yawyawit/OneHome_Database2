<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'My Reviews';
$rows = db_all("SELECT r.*, e.name ename, s.name sname FROM reviews r JOIN bookings b ON b.id=r.booking_id JOIN users e ON e.id=r.employee_id JOIN services s ON s.id=b.service_id WHERE r.customer_id=? ORDER BY r.id DESC", [$u['id']]);
require INC . '/header.php'; ?>
<h2>My Reviews</h2>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">You have not written any reviews yet. Reviews can be written for completed bookings.
    </div><?php endif; ?>
<?php foreach ($rows as $r): ?>
    <div class="card mb-2">
        <div class="card-body"><b><?= e($r['ename']) ?></b> &mdash; <?= e($r['sname']) ?>     <?= stars($r['rating']) ?>
            <div><?= e($r['comment'] ?: '(no comment)') ?></div><small
                class="text-muted"><?= fdate($r['created_at']) ?></small>
        </div>
    </div>
<?php endforeach; ?>
<?php require INC . '/footer.php'; ?>