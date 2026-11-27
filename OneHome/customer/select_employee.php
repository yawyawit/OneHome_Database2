<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$sid = (int) ($_GET['service_id'] ?? 0);
$service = db_one("SELECT * FROM services WHERE id=? AND is_active=1", [$sid]);
if (!$service) {
    flash('Service not found.', 'warning');
    redirect('customer/services.php');
}
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
if ($date && (!strtotime($date) || $date < date('Y-m-d'))) {
    $date = '';
    flash('Choose a future date.', 'warning');
}
$emps = db_all("SELECT u.id,u.name,u.avatar,u.skills,u.available_days,
    (SELECT ROUND(AVG(rating),1) FROM reviews r WHERE r.employee_id=u.id) avg_rating,
    (SELECT COUNT(*) FROM reviews r WHERE r.employee_id=u.id) rc
    FROM users u JOIN employee_services es ON es.employee_id=u.id
    WHERE es.service_id=? AND u.role='employee' AND u.status='active' AND u.is_available=1
    ORDER BY avg_rating DESC, u.name", [$sid]);
if ($date) {
    $emps = array_filter($emps, function ($employee) use ($date, $time) {
        return works_on($employee, $date) && ($time === '' || employee_free($employee['id'], $date, $time));
    });
}
$title = 'Choose a Provider';
require INC . '/header.php';
?>
<div class="mb-4">
    <a class="small text-decoration-none" href="<?= url('customer/services.php') ?>"><i class="bi bi-arrow-left"></i> Services</a>
    <h2 class="mt-2">Choose your provider</h2>
    <div class="page-subtitle"><?= e($service['name']) ?> · Starting at <?= money($service['price']) ?></div>
</div>
<form class="card card-body mb-3" method="get">
    <input type="hidden" name="service_id" value="<?= $sid ?>">
    <div class="row g-2 align-items-end">
        <div class="col-sm-5"><label class="form-label">Date</label><input type="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= e($date) ?>" class="form-control"></div>
        <div class="col-sm-4"><label class="form-label">Time</label><input type="time" name="time" value="<?= e($time) ?>" class="form-control"></div>
        <div class="col-sm-3"><button class="btn btn-primary w-100">Check availability</button></div>
    </div>
</form>
<?php if (!$emps): ?>
    <div class="empty-state"><i class="bi bi-person-x fs-3 d-block mb-2"></i>No providers available. Try another date or time.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($emps as $employee): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <?= avatar($employee['name'], $employee['avatar'], 52) ?>
                            <div><h5 class="mb-1"><?= e($employee['name']) ?></h5><div class="small"><?= rating_text($employee['avg_rating'], $employee['rc']) ?></div></div>
                        </div>
                        <p class="small text-muted mb-2"><?= e($employee['skills'] ?: 'Verified service provider') ?></p>
                        <div class="small text-muted mb-3"><i class="bi bi-calendar3 me-1"></i><?= e(str_replace(',', ', ', $employee['available_days'])) ?></div>
                        <a class="btn btn-primary w-100" href="<?= url('customer/book_service.php?service_id=' . $sid . '&employee_id=' . $employee['id'] . '&date=' . urlencode($date) . '&time=' . urlencode($time)) ?>">Choose provider</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require INC . '/footer.php'; ?>
