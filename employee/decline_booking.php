<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
post_only();
$u = current_user();
$b = get_booking((int) $_POST['id']);
if (!$b || $b['employee_id'] != $u['id'] || $b['status'] !== 'Pending') {
    flash('That request cannot be declined.', 'danger');
    redirect('employee/booking_requests.php');
}
db_exec("UPDATE bookings SET status='Declined', decline_reason=? WHERE id=?", [trim($_POST['reason'] ?? '') ?: 'No reason given', $b['id']]);
flash('Booking declined.', 'info');
redirect('employee/booking_requests.php');
