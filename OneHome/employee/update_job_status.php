<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
post_only();
$u = current_user();
$b = get_booking((int) $_POST['id']);
$a = $_POST['action'] ?? '';
if (!$b || $b['employee_id'] != $u['id']) {
    flash('Job not found.', 'danger');
    redirect('employee/my_jobs.php');
}
if ($a === 'start' && $b['status'] === 'Confirmed') {
    db_exec("UPDATE bookings SET status='Ongoing' WHERE id=?", [$b['id']]);
    flash('Job started.');
} elseif ($a === 'complete' && $b['status'] === 'Ongoing') {
    db_exec("UPDATE bookings SET status='Completed' WHERE id=?", [$b['id']]);
    flash('Job completed! The customer can now pay and leave a review.');
} elseif ($a === 'paid' && $b['status'] === 'Completed' && $b['pstatus'] === 'Unpaid' && $b['pmethod'] === 'cash') {
    db_exec("UPDATE payments SET status='Paid', paid_at=NOW() WHERE booking_id=?", [$b['id']]);
    flash('Cash payment recorded.');
} else {
    flash('That action is not allowed for this job.', 'danger');
}
redirect('employee/my_jobs.php');
