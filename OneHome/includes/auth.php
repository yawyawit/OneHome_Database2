<?php
// Start a protected session before loading application data.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
    if (!empty($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800) {
        $_SESSION = [];
        session_regenerate_id(true);
    } elseif (empty($_SESSION['last_regenerated']) || time() - $_SESSION['last_regenerated'] > 900) {
        session_regenerate_id(true);
        $_SESSION['last_regenerated'] = time();
    }
    $_SESSION['last_activity'] = time();
}
define('INC', __DIR__);
require_once INC . '/dbconn.php';
require_once INC . '/functions.php';
require_once INC . '/notifications.php';

function current_user()
{
    static $loaded = false;
    static $user = null;
    if (!$loaded) {
        $loaded = true;
        if (!empty($_SESSION['uid'])) {
            $user = db_one("SELECT * FROM users WHERE id=?", [(int) $_SESSION['uid']]);
        }
    }
    return $user;
}

function home_for($role)
{
    return $role . '/dashboard.php';
}

function require_role($role)
{
    $u = current_user();
    if (!$u || $u['status'] !== 'active') {
        unset($_SESSION['uid']);
        flash('Please log in first.', 'warning');
        redirect('login.php');
    }
    if ($u['role'] !== $role) {
        flash('Access denied.', 'danger');
        redirect(home_for($u['role']));
    }
}
