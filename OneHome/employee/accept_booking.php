<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
post_only();
$u = current_user();
$b = get_booking((int) $_POST['id']);
if (!$b || $b['employee_id'] != $u['id'] || $b['status'] !== 'Pending') {
    flash('That request cannot be accepted.', 'danger');
    redirect('employee/booking_requests.php');
}
if (!employee_free($u['id'], $b['booking_date'], $b['booking_time'], $b['id'])) {
    flash('You already have a confirmed job around that time. Decline this request instead.', 'danger');
    redirect('employee/booking_requests.php');
}
db_exec("UPDATE bookings SET status='Confirmed' WHERE id=?", [$b['id']]);
flash('Booking accepted! It is now in your schedule.');
redirect('employee/my_schedule.php');
