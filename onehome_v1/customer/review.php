<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$b = get_booking((int) ($_GET['id'] ?? 0));
if (!$b || $b['customer_id'] != $u['id'] || $b['status'] !== 'Completed') {
    flash('You can only review completed bookings.', 'warning');
    redirect('customer/bookings.php');
}
if ($b['reviewed']) {
    flash('You already reviewed this booking.', 'info');
    redirect('customer/my_reviews.php');
}
$title = 'Rate & Review';
require INC . '/header.php'; ?>
<h2>Rate <?= e($b['ename']) ?></h2>
<p class="text-muted"><?= e($b['sname']) ?> &mdash; <?= fdate($b['booking_date']) ?></p>
<form method="post" action="<?= url('actions/process_review.php') ?>" class="card card-body" style="max-width:600px">
    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
    <label class="form-label">Rating</label>
    <div class="mb-3"><?php for ($i = 5; $i >= 1; $i--): ?><label class="me-3"><input type="radio" name="rating"
                    value="<?= $i ?>" required> <?= $i ?> <span class="star">★</span></label><?php endfor; ?></div>
    <label class="form-label">Comment (optional)</label><textarea name="comment" rows="4"
        class="form-control mb-3"></textarea>
    <div><button class="btn btn-primary">Submit Review</button> <a class="btn btn-secondary"
            href="<?= url('customer/booking_details.php?id=' . $b['id']) ?>">Cancel</a></div>
</form>
<?php require INC . '/footer.php'; ?>