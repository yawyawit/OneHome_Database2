<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
post_only();

$user = current_user();
$booking = get_booking((int) ($_POST['id'] ?? 0));
$action = $_POST['action'] ?? '';

if (!$booking || $booking['employee_id'] != $user['id']) {
    flash('Job not found.', 'danger');
    redirect('employee/my_jobs.php');
}

if ($action === 'start' && $booking['status'] === 'Confirmed') {
    db_exec("UPDATE bookings SET status = 'Ongoing' WHERE id = ?", [$booking['id']]);
    flash('Job started.');
} elseif ($action === 'complete' && $booking['status'] === 'Ongoing') {
    db_exec("UPDATE bookings SET status = 'Completed' WHERE id = ?", [$booking['id']]);
    flash('Job completed! The customer can now pay and leave a review.');
} elseif (
    $action === 'paid' &&
    $booking['status'] === 'Completed' &&
    $booking['pstatus'] === 'Unpaid' &&
    $booking['pmethod'] === 'cash'
) {
    db_exec(
        "UPDATE payments SET status = 'Paid', paid_at = NOW() WHERE booking_id = ?",
        [$booking['id']]
    );
    flash('Cash payment recorded.');
} else {
    flash('That action is not allowed for this job.', 'danger');
}

redirect('employee/my_jobs.php');
