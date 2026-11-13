<?php
require_once __DIR__ . '/../includes/auth.php';
post_only();
$role = ($_POST['role'] ?? '') === 'employee' ? 'employee' : 'customer';   // admins can never be created here
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$addr = trim($_POST['address'] ?? '');
$pass = $_POST['password'] ?? '';
$err = '';
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
    $err = 'Please enter your name and a valid email.';
elseif (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone))
    $err = 'Please enter a valid contact number.';
elseif (strlen($pass) < 6)
    $err = 'Password must be at least 6 characters.';
elseif ($pass !== ($_POST['confirm'] ?? ''))
    $err = 'Passwords do not match.';
elseif (db_val("SELECT COUNT(*) FROM users WHERE email=?", [$email]) > 0)
    $err = 'That email is already registered.';
if ($err) {
    flash($err, 'danger');
    redirect('register.php');
}

$status = $role === 'employee' ? 'pending' : 'active';
$id = db_exec(
    "INSERT INTO users (name,email,password,phone,address,role,status,skills) VALUES (?,?,?,?,?,?,?,?)",
    [$name, $email, password_hash($pass, PASSWORD_DEFAULT), $phone, $addr, $role, $status, trim($_POST['skills'] ?? '')]
);
if ($role === 'employee') {
    foreach (($_POST['services'] ?? []) as $sid)
        db_exec("INSERT IGNORE INTO employee_services (employee_id,service_id) VALUES (?,?)", [$id, (int) $sid]);
    flash('Registered! Please wait for the admin to approve your account before logging in.', 'info');
} else {
    flash('Account created! You can log in now.');
}
redirect('login.php');
