<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
post_only();

$user = current_user();
$booking = get_booking((int) ($_POST['id'] ?? 0));

if (!$booking || $booking['customer_id'] != $user['id']) {
    flash('Booking not found.', 'danger');
    redirect('customer/bookings.php');
}

if (!in_array($booking['status'], ['Pending', 'Confirmed'], true)) {
    flash('This booking can no longer be cancelled.', 'danger');
    redirect('customer/booking_details.php?id=' . $booking['id']);
}

db_exec("UPDATE bookings SET status = 'Cancelled' WHERE id = ?", [$booking['id']]);
flash('Your booking was cancelled.', 'info');
redirect('customer/bookings.php');
