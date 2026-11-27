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
elseif (strlen($pass) < 10)
    $err = 'Password must be at least 10 characters.';
elseif ($pass !== ($_POST['confirm'] ?? ''))
    $err = 'Passwords do not match.';
elseif (db_val("SELECT COUNT(*) FROM users WHERE email=?", [$email]) > 0)
    $err = 'That email is already registered.';
$selected_services = array_values(array_unique(array_filter(array_map('intval', $_POST['services'] ?? []))));
if ($role === 'employee' && !$selected_services) {
    $err = 'Please select at least one service you can provide.';
} elseif ($role === 'employee') {
    foreach ($selected_services as $service_id) {
        if (!db_val("SELECT COUNT(*) FROM services WHERE id=? AND is_active=1", [$service_id])) {
            $err = 'One of the selected services is not available.';
            break;
        }
    }
}
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
    foreach ($selected_services as $sid) {
        if (db_val("SELECT COUNT(*) FROM services WHERE id=? AND is_active=1", [$sid])) {
            db_exec("INSERT IGNORE INTO employee_services (employee_id,service_id) VALUES (?,?)", [$id, $sid]);
        }
    }
    flash('Registered! Please wait for the admin to approve your account before logging in.', 'info');
} else {
    flash('Account created! You can log in now.');
}
redirect('login.php');
