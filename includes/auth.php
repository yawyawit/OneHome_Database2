<?php
// Include this at the top of EVERY page. It starts the session and loads helpers.
if (session_status() === PHP_SESSION_NONE)
    session_start();
define('INC', __DIR__);
require_once INC . '/dbconn.php';
require_once INC . '/functions.php';
require_once INC . '/notifications.php';

function current_user()
{
    static $user = null;
    if ($user === null && !empty($_SESSION['uid'])) {
        $user = db_one("SELECT * FROM users WHERE id=?", [$_SESSION['uid']]);
    }
    return $user;
}

function home_for($role)
{
    return $role . '/dashboard.php';
}

// Call this on every protected page: require_role('admin'), require_role('employee'), require_role('customer')
function require_role($role)
{
    $u = current_user();
    if (!$u || $u['status'] !== 'active') {
        unset($_SESSION['uid']);
        flash('Please log in first.', 'warning');
        redirect('login.php');
    }
    if ($u['role'] !== $role) {
        flash('Access denied. You are not allowed to open that page.', 'danger');
        redirect(home_for($u['role']));
    }
}
