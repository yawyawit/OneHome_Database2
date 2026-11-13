<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
post_only();
$u = current_user();
$b = get_booking((int) $_POST['id']);
if (!$b || $b['customer_id'] != $u['id']) {
    flash('Booking not found.', 'danger');
    redirect('customer/bookings.php');
}
if (!in_array($b['status'], ['Pending', 'Confirmed'])) {
    flash('This booking can no longer be cancelled.', 'danger');
    redirect('customer/booking_details.php?id=' . $b['id']);
}
db_exec("UPDATE bookings SET status='Cancelled' WHERE id=?", [$b['id']]);
flash('Your booking was cancelled.', 'info');
redirect('customer/bookings.php');
