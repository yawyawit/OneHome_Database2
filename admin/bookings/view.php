<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$b = get_booking((int) ($_GET['id'] ?? $_POST['id'] ?? 0));
if (!$b) {
  flash('Booking not found.', 'danger');
  redirect('admin/bookings/index.php');
}
$statuses = ['Pending', 'Confirmed', 'Ongoing', 'Completed', 'Cancelled', 'Declined'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['status'] ?? '', $statuses)) {   // admin can fix booking problems
  db_exec("UPDATE bookings SET status=? WHERE id=?", [$_POST['status'], $b['id']]);
  flash('Booking status updated.');
  redirect('admin/bookings/view.php?id=' . $b['id']);
}
$review = db_one("SELECT * FROM reviews WHERE booking_id=?", [$b['id']]);
$title = 'Booking Details';
require INC . '/header.php'; ?>
<h2>Booking #<?= $b['id'] ?></h2>
<?php booking_info($b); ?>
<?php if ($review): ?>
  <div class="card mb-3">
    <div class="card-body"><b>Review:</b> <?= stars($review['rating']) ?>   <?= e($review['comment']) ?></div>
  </div><?php endif; ?>
<form method="post" class="card card-body mb-3" style="max-width:450px"
  data-confirm="Change the status of this booking?">
  <input type="hidden" name="id" value="<?= $b['id'] ?>">
  <label class="form-label">Resolve a problem: change booking status</label>
  <div class="input-group"><select name="status" class="form-select"><?php foreach ($statuses as $s): ?>
        <option <?= $s === $b['status'] ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
    </select><button class="btn btn-primary">Update</button></div>
</form>
<?php if ($b['payid']): ?><a class="btn btn-outline-primary"
    href="<?= url('admin/payments/view.php?id=' . $b['payid']) ?>">View payment</a><?php endif; ?>
<a class="btn btn-secondary" href="index.php">Back</a>
<?php require INC . '/footer.php'; ?>