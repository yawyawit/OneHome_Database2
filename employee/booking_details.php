<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$b = get_booking((int) ($_GET['id'] ?? 0));
if (!$b || $b['employee_id'] != $u['id']) {
    flash('Booking not found.', 'danger');
    redirect('employee/my_jobs.php');
}
$title = 'Job Details';
require INC . '/header.php'; ?>
<h2>Job #<?= $b['id'] ?></h2>
<?php booking_info($b); ?>
<div class="mb-3"><?= job_buttons($b) ?></div>
<a class="btn btn-secondary" href="<?= url('employee/my_jobs.php') ?>">Back to My Jobs</a>
<?php require INC . '/footer.php'; ?>