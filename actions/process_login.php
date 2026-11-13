<?php
require_once __DIR__ . '/../includes/auth.php';
post_only();
$email = trim($_POST['email'] ?? '');
$u = db_one("SELECT * FROM users WHERE email=?", [$email]);
if (!$u || !password_verify($_POST['password'] ?? '', $u['password'])) {
    flash('Wrong email or password.', 'danger');
    redirect('login.php');
}
if ($u['status'] === 'pending') {
    flash('Your employee account is still waiting for admin approval.', 'warning');
    redirect('login.php');
}
if ($u['status'] === 'inactive') {
    flash('Your account is deactivated. Please contact the admin.', 'danger');
    redirect('login.php');
}
session_regenerate_id(true);
$_SESSION['uid'] = $u['id'];
redirect(home_for($u['role']));
