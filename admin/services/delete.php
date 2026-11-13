<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
post_only();
$id = (int) $_POST['id'];
if (db_val("SELECT COUNT(*) FROM bookings WHERE service_id=?", [$id]) > 0) {
    db_exec("UPDATE services SET is_active=0 WHERE id=?", [$id]);
    flash('This service has bookings, so it was hidden from customers instead of deleted.', 'info');
} else {
    db_exec("DELETE FROM services WHERE id=?", [$id]);
    flash('Service removed.');
}
redirect('admin/services/index.php');
