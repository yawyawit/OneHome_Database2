<?php
// Shared security checks for all pages in One Home.
// Include this file before any HTML output.

if (session_status() === PHP_SESSION_NONE) {
    // Give this application its own session cookie name.
    session_name('ONEHOMESESSID');

    // Use safer session settings to help prevent session fixation and cookie theft.
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');

    $is_https = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $is_https,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

define('INC', __DIR__);
require_once INC . '/dbconn.php';
require_once INC . '/functions.php';
require_once INC . '/notifications.php';

/**
 * Return the signed-in user from the database.
 * The role and account status are always read from the database,
 * not trusted from a URL, form, or browser value.
 */
function current_user()
{
    static $loaded = false;
    static $user = null;

    if (!$loaded) {
        $loaded = true;

        if (!empty($_SESSION['uid'])) {
            $user_id = (int) $_SESSION['uid'];

            if ($user_id > 0) {
                $user = db_one("SELECT * FROM users WHERE id = ? LIMIT 1", [$user_id]);
            }
        }
    }

    return $user;
}

function home_for($role)
{
    $pages = [
        'admin' => 'admin/dashboard.php',
        'employee' => 'employee/dashboard.php',
        'customer' => 'customer/dashboard.php'
    ];

    return $pages[$role] ?? 'login.php';
}

/**
 * Require an active login with the exact role needed by the page.
 * Every customer, employee, and admin page must call this before output.
 */
function require_role($role)
{
    // Do not allow browsers or shared computers to reuse private pages after logout.
    if (!headers_sent()) {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    $allowed_roles = ['admin', 'employee', 'customer'];
    if (!in_array($role, $allowed_roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }

    $user = current_user();

    // Not logged in, removed account, or account waiting for approval/deactivated.
    if (!$user || $user['status'] !== 'active' || !in_array($user['role'], $allowed_roles, true)) {
        unset($_SESSION['uid']);
        session_regenerate_id(true);
        flash('Please log in using an active account.', 'warning');
        redirect('login.php');
    }

    // A valid login is not enough: the account must have the correct role.
    if ($user['role'] !== $role) {
        flash('Access denied. You are not allowed to open that page.', 'danger');
        redirect(home_for($user['role']));
    }
}
