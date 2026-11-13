<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
post_only();

$id = (int) ($_POST['id'] ?? 0);

// Keep a service hidden if it is already part of a booking.
$booking_count = db_val(
    "SELECT COUNT(*) FROM bookings WHERE service_id = ?",
    [$id]
);

if ($booking_count > 0) {
    db_exec("UPDATE services SET is_active = 0 WHERE id = ?", [$id]);
    flash('This service has bookings, so it was hidden instead of deleted.', 'info');
} else {
    db_exec("DELETE FROM services WHERE id = ?", [$id]);
    flash('Service removed.');
}

redirect('admin/services/index.php');
