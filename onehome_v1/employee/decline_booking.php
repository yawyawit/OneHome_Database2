<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
post_only();

$user = current_user();
$booking = get_booking((int) ($_POST['id'] ?? 0));

if (!$booking || $booking['employee_id'] != $user['id'] || $booking['status'] !== 'Pending') {
    flash('That request cannot be declined.', 'danger');
    redirect('employee/booking_requests.php');
}

$reason = trim($_POST['reason'] ?? '');
if ($reason === '') {
    $reason = 'No reason given';
}

db_exec(
    "UPDATE bookings SET status = 'Declined', decline_reason = ? WHERE id = ?",
    [$reason, $booking['id']]
);

flash('Booking declined.', 'info');
redirect('employee/booking_requests.php');
