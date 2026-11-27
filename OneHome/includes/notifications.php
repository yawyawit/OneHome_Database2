<?php
function show_flash()
{
    if (!empty($_SESSION['flash'])) {
        list($m, $t) = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($t) . ' alert-dismissible fade show">' . e($m) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}
function new_requests_count($eid)
{
    return db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND status='Pending'", [$eid]);
}
function pending_employees_count()
{
    return db_val("SELECT COUNT(*) FROM users WHERE role='employee' AND status='pending'");
}
