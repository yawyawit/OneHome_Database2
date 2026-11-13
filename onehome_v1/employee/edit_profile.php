<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Edit Profile';
$all = db_all("SELECT * FROM services WHERE is_active=1 ORDER BY name");
$mine = array_column(db_all("SELECT service_id FROM employee_services WHERE employee_id=?", [$u['id']]), 'service_id');
require INC . '/header.php'; ?>
<h2>Edit Profile</h2>
<div class="card mb-4">
    <div class="card-header">Work Profile</div>
    <div class="card-body">
        <form method="post" enctype="multipart/form-data" action="<?= url('actions/process_profile.php') ?>">
            <input type="hidden" name="action" value="employee">
            <div class="mb-3"><?= avatar($u['name'], $u['avatar'], 70) ?></div>
            <label class="form-label">Profile picture (jpg/png, max 2MB)</label><input type="file" name="avatar"
                accept="image/*" class="form-control mb-3">
            <label class="form-label">Skills &amp; specializations</label><input name="skills"
                value="<?= e($u['skills']) ?>" class="form-control mb-3">
            <label class="form-label">Services I can perform</label>
            <div class="mb-3"><?php foreach ($all as $s): ?>
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="services[]"
                            value="<?= $s['id'] ?>" id="s<?= $s['id'] ?>" <?= in_array($s['id'], $mine) ? 'checked' : '' ?>><label class="form-check-label" for="s<?= $s['id'] ?>"><?= e($s['name']) ?></label></div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-primary">Save Work Profile</button> <a class="btn btn-outline-secondary"
                href="<?= url('employee/availability.php') ?>">Set availability</a>
        </form>
    </div>
</div>
<?php account_forms($u); ?>
<?php require INC . '/footer.php'; ?>