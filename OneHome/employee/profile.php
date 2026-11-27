<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'My Profile';
$svc = db_all("SELECT s.name FROM employee_services es JOIN services s ON s.id=es.service_id WHERE es.employee_id=?", [$u['id']]);
$avg = db_val("SELECT ROUND(AVG(rating),1) FROM reviews WHERE employee_id=?", [$u['id']]);
$rc = db_val("SELECT COUNT(*) FROM reviews WHERE employee_id=?", [$u['id']]);
require INC . '/header.php'; ?>
<h2>My Profile</h2>
<div class="card" style="max-width:650px">
    <div class="card-body">
        <div class="mb-3"><?= avatar($u['name'], $u['avatar'], 90) ?></div>
        <p><b>Name:</b> <?= e($u['name']) ?></p>
        <p><b>Email:</b> <?= e($u['email']) ?></p>
        <p><b>Contact:</b> <?= e($u['phone']) ?></p>
        <p><b>Skills:</b> <?= e($u['skills'] ?: 'Not listed') ?></p>
        <p><b>Services I offer:</b> <?= e(implode(', ', array_column($svc, 'name')) ?: 'None selected') ?></p>
        <p><b>Working days:</b> <?= e(str_replace(',', ', ', $u['available_days'])) ?></p>
        <p><b>Rating:</b> <?= rating_text($avg, $rc) ?></p>
        <a class="btn btn-primary" href="<?= url('employee/edit_profile.php') ?>">Edit Profile</a>
        <a class="btn btn-outline-primary" href="<?= url('employee/availability.php') ?>">Availability</a>
    </div>
</div>
<?php require INC . '/footer.php'; ?>