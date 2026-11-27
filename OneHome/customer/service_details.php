<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$s = db_one("SELECT * FROM services WHERE id=? AND is_active=1", [(int) ($_GET['id'] ?? 0)]);
if (!$s) {
    flash('Service not found.', 'danger');
    redirect('customer/services.php');
}
$count = db_val("SELECT COUNT(*) FROM employee_services es JOIN users u ON u.id=es.employee_id WHERE es.service_id=? AND u.status='active'", [$s['id']]);
$title = $s['name'];
require INC . '/header.php'; ?>
<div class="card">
    <div class="card-body text-center p-5">
        <i class="bi <?= e($s['icon']) ?> service-icon" style="font-size:5rem"></i>
        <h2 class="mt-3"><?= e($s['name']) ?></h2>
        <p class="lead"><?= e($s['description']) ?></p>
        <p class="fs-4 fw-bold">Starts at <?= money($s['price']) ?></p>
        <p class="text-muted"><?= $count ?> employee(s) offer this service.</p>
        <a class="btn btn-primary btn-lg" href="<?= url('customer/select_employee.php?service_id=' . $s['id']) ?>">Book
            Now</a>
        <a class="btn btn-secondary btn-lg" href="<?= url('customer/services.php') ?>">Back</a>
    </div>
</div>
<?php require INC . '/footer.php'; ?>