<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$sid = (int) ($_GET['service_id'] ?? 0);
$eid = (int) ($_GET['employee_id'] ?? 0);
$service = db_one("SELECT * FROM services WHERE id=? AND is_active=1", [$sid]);
$emp = db_one("SELECT u.* FROM users u JOIN employee_services es ON es.employee_id=u.id WHERE u.id=? AND es.service_id=? AND u.role='employee' AND u.status='active'", [$eid, $sid]);
if (!$service || !$emp) {
    flash('Please choose a service and employee first.', 'warning');
    redirect('customer/services.php');
}
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$title = 'Book a Service';
require INC . '/header.php'; ?>
<h2>Book a Service</h2>
<div class="row">
    <div class="col-md-7">
        <form method="post" enctype="multipart/form-data" action="<?= url('actions/process_booking.php') ?>"
            class="card card-body">
            <input type="hidden" name="service_id" value="<?= $sid ?>"><input type="hidden" name="employee_id"
                value="<?= $eid ?>">
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Preferred Date</label><input type="date"
                        name="date" min="<?= date('Y-m-d') ?>" value="<?= e($date) ?>" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3"><label class="form-label">Preferred Time</label><input type="time"
                        name="time" value="<?= e($time) ?>" class="form-control" required></div>
            </div>
            <label class="form-label">Home Address / Service Location</label><textarea name="address" rows="2"
                class="form-control mb-3" required><?= e($u['address']) ?></textarea>
            <label class="form-label">Describe the problem or work needed</label><textarea name="description" rows="3"
                class="form-control mb-3" required></textarea>
            <label class="form-label">Photo (optional, max 2MB)</label><input type="file" name="photo" accept="image/*"
                class="form-control mb-3">
            <label class="form-label">Payment Method</label>
            <div class="mb-3">
                <div class="form-check"><input class="form-check-input" type="radio" name="method" value="cash" id="m1"
                        checked><label class="form-check-label" for="m1">Pay after service (cash)</label></div>
                <div class="form-check"><input class="form-check-input" type="radio" name="method" value="card"
                        id="m2"><label class="form-check-label" for="m2">Pay online by card (simulated &mdash; after the
                        job is completed)</label>
                </div>
            </div>
            <button class="btn btn-success btn-lg">Confirm Booking</button>
        </form>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Summary</div>
            <div class="card-body">
                <p><b>Service:</b> <?= e($service['name']) ?></p>
                <p><b>Employee:</b> <?= e($emp['name']) ?></p>
                <p><b>Works on:</b> <?= e(str_replace(',', ', ', $emp['available_days'])) ?></p>
                <p class="fs-5 mb-0"><b>Estimated price:</b> <?= money($service['price']) ?></p>
            </div>
        </div>
    </div>
</div>
<?php require INC . '/footer.php'; ?>