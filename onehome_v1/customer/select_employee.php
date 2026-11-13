<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$sid = (int) ($_GET['service_id'] ?? 0);
$service = db_one("SELECT * FROM services WHERE id=? AND is_active=1", [$sid]);
if (!$service) {
    flash('Service not found.', 'danger');
    redirect('customer/services.php');
}
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
if ($date && (!strtotime($date) || $date < date('Y-m-d'))) {
    $date = '';
    flash('Please pick a date that is today or later.', 'warning');
}
$emps = db_all("SELECT u.*, (SELECT ROUND(AVG(rating),1) FROM reviews r WHERE r.employee_id=u.id) avg_rating,
    (SELECT COUNT(*) FROM reviews r WHERE r.employee_id=u.id) rc,
    (SELECT GROUP_CONCAT(s2.name SEPARATOR ', ') FROM employee_services e2 JOIN services s2 ON s2.id=e2.service_id WHERE e2.employee_id=u.id) services_list
    FROM users u JOIN employee_services es ON es.employee_id=u.id
    WHERE es.service_id=? AND u.role='employee' AND u.status='active' AND u.is_available=1 ORDER BY avg_rating DESC", [$sid]);
if ($date) {   // only keep employees who work that day and have no overlapping job
    $emps = array_filter($emps, function ($e) use ($date, $time) {
        return works_on($e, $date) && ($time === '' || employee_free($e['id'], $date, $time)); });
}
$title = 'Choose Employee';
require INC . '/header.php'; ?>
<h2>Choose an Employee for <?= e($service['name']) ?></h2>
<form class="row g-2 align-items-end my-3" method="get">
    <input type="hidden" name="service_id" value="<?= $sid ?>">
    <div class="col-md-3"><label class="form-label">Preferred date</label><input type="date" name="date"
            min="<?= date('Y-m-d') ?>" value="<?= e($date) ?>" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Preferred time</label><input type="time" name="time"
            value="<?= e($time) ?>" class="form-control"></div>
    <div class="col-md-3"><button class="btn btn-outline-primary">Check availability</button></div>
</form>
<?php if (!$date): ?>
    <p class="text-muted">Tip: pick a date and time to see only the employees who are available.</p><?php endif; ?>
<?php if (!$emps): ?>
    <div class="alert alert-warning">No employees are available for that schedule. Try a different date or time.</div>
<?php endif; ?>
<?php foreach ($emps as $e): ?>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-1"><?= avatar($e['name'], $e['avatar']) ?></div>
                <div class="col-md-8">
                    <h5 class="mb-1"><?= e($e['name']) ?></h5>
                    <div><?= rating_text($e['avg_rating'], $e['rc']) ?></div>
                    <div class="small"><b>Skills:</b> <?= e($e['skills'] ?: 'Not listed') ?></div>
                    <div class="small"><b>Services:</b> <?= e($e['services_list']) ?></div>
                    <div class="small"><b>Available days:</b> <?= e(str_replace(',', ', ', $e['available_days'])) ?>
                        &middot; <b>Price:</b> from <?= money($service['price']) ?></div>
                    <?php foreach (db_all("SELECT rating, comment FROM reviews WHERE employee_id=? AND comment<>'' ORDER BY id DESC LIMIT 2", [$e['id']]) as $r): ?>
                        <div class="small text-muted fst-italic">"<?= e($r['comment']) ?>" <?= stars($r['rating']) ?></div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-3 text-md-end mt-2"><a class="btn btn-primary"
                        href="<?= url('customer/book_service.php?service_id=' . $sid . '&employee_id=' . $e['id'] . '&date=' . urlencode($date) . '&time=' . urlencode($time)) ?>">Select
                        Employee</a></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<a href="<?= url('customer/services.php') ?>">&laquo; Back to services</a>
<?php require INC . '/footer.php'; ?>