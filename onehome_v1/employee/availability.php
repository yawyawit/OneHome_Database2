<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$all = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = array_values(array_intersect($all, $_POST['days'] ?? []));
    db_exec("UPDATE users SET available_days=?, is_available=? WHERE id=?", [implode(',', $days), isset($_POST['is_available']) ? 1 : 0, $u['id']]);
    flash('Availability saved.');
    redirect('employee/availability.php');
}
$mine = explode(',', $u['available_days']);
$title = 'Availability';
require INC . '/header.php'; ?>
<h2>My Availability</h2>
<form method="post" class="card card-body" style="max-width:500px">
    <label class="form-label">Days I can work</label>
    <div class="mb-3"><?php foreach ($all as $d): ?>
            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="days[]"
                    value="<?= $d ?>" id="d<?= $d ?>" <?= in_array($d, $mine) ? 'checked' : '' ?>><label
                    class="form-check-label" for="d<?= $d ?>"><?= $d ?></label></div><?php endforeach; ?>
    </div>
    <div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" name="is_available" id="ia"
            <?= $u['is_available'] ? 'checked' : '' ?>><label class="form-check-label" for="ia">I am accepting new
            bookings
            (turn off for vacation/leave)</label></div>
    <button class="btn btn-primary">Save</button>
</form>
<?php require INC . '/footer.php'; ?>