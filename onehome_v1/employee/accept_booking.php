<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
post_only();

$user = current_user();
$booking_id = (int) ($_POST['id'] ?? 0);
$booking = get_booking($booking_id);

if (!$booking || $booking['employee_id'] != $user['id'] || $booking['status'] !== 'Pending') {
    flash('That request cannot be accepted.', 'danger');
    redirect('employee/booking_requests.php');
}

if (!employee_free($user['id'], $booking['booking_date'], $booking['booking_time'], $booking['id'])) {
    flash('You already have a confirmed job around that time. Please decline this request instead.', 'danger');
    redirect('employee/booking_requests.php');
}

db_exec("UPDATE bookings SET status = 'Confirmed' WHERE id = ?", [$booking['id']]);
flash('Booking accepted! It is now in your schedule.');
redirect('employee/my_schedule.php');
