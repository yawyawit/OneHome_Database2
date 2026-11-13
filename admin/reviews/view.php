<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$r = db_one("SELECT r.*, c.name cname, e.name ename FROM reviews r JOIN users c ON c.id=r.customer_id JOIN users e ON e.id=r.employee_id WHERE r.id=?", [(int) ($_GET['id'] ?? $_POST['id'] ?? 0)]);
if (!$r) {
    flash('Review not found.', 'danger');
    redirect('admin/reviews/index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {   // remove inappropriate review
    db_exec("DELETE FROM reviews WHERE id=?", [$r['id']]);
    flash('Review deleted.');
    redirect('admin/reviews/index.php');
}
$b = get_booking($r['booking_id']);
$title = 'Review Details';
require INC . '/header.php'; ?>
<h2>Review #<?= $r['id'] ?></h2>
<div class="card mb-3">
    <div class="card-body">
        <p><b><?= e($r['cname']) ?></b> reviewed <b><?= e($r['ename']) ?></b></p>
        <p class="fs-4"><?= stars($r['rating']) ?></p>
        <p><?= e($r['comment'] ?: '(no comment)') ?></p><small class="text-muted"><?= fdate($r['created_at']) ?></small>
    </div>
</div>
<h5>Related booking</h5><?php booking_info($b); ?>
<form method="post" class="d-inline"
    data-confirm="Delete this review permanently? Only do this for inappropriate reviews."><input type="hidden"
        name="id" value="<?= $r['id'] ?>"><button class="btn btn-danger">Delete Review</button></form>
<a class="btn btn-secondary" href="index.php">Back</a>
<?php require INC . '/footer.php'; ?>