<?php
require_once __DIR__ . '/../includes/auth.php';
post_only();
$u = current_user();
if (!$u)
    redirect('login.php');
$back = $u['role'] === 'admin' ? 'admin/profile.php' : $u['role'] . '/edit_profile.php';
$a = $_POST['action'] ?? '';

if ($a === 'info') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $addr = trim($_POST['address'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
        flash('Please enter a valid name, email and contact number.', 'danger');
        redirect($back);
    }
    if ($email !== $u['email'] || $phone !== (string) $u['phone']) {
        if (!password_verify($_POST['current_password'] ?? '', $u['password'])) {
            flash('Enter your current password to change your email or contact number.', 'danger');
            redirect($back);
        }
    }
    if (db_val("SELECT COUNT(*) FROM users WHERE email=? AND id<>?", [$email, $u['id']]) > 0) {
        flash('That email is already used by another account.', 'danger');
        redirect($back);
    }
    db_exec("UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?", [$name, $email, $phone, $addr, $u['id']]);
    flash('Profile updated.');
} elseif ($a === 'password') {
    if (!password_verify($_POST['current_password'] ?? '', $u['password']))
        flash('Current password is wrong.', 'danger');
    elseif (strlen($_POST['new_password']) < 6)
        flash('New password must be at least 6 characters.', 'danger');
    elseif ($_POST['new_password'] !== $_POST['confirm_password'])
        flash('New passwords do not match.', 'danger');
    else {
        db_exec("UPDATE users SET password=? WHERE id=?", [password_hash($_POST['new_password'], PASSWORD_DEFAULT), $u['id']]);
        flash('Password changed.');
    }
} elseif ($a === 'employee' && $u['role'] === 'employee') {
    db_exec("UPDATE users SET skills=? WHERE id=?", [trim($_POST['skills'] ?? ''), $u['id']]);
    db_exec("DELETE FROM employee_services WHERE employee_id=?", [$u['id']]);
    foreach (($_POST['services'] ?? []) as $sid)
        db_exec("INSERT IGNORE INTO employee_services (employee_id,service_id) VALUES (?,?)", [$u['id'], (int) $sid]);
    $pic = save_upload('avatar', 'images/profiles');
    if ($pic)
        db_exec("UPDATE users SET avatar=? WHERE id=?", [$pic, $u['id']]);
    flash('Employee profile updated.');
}
redirect($back);
