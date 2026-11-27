<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
post_only();
$u = current_user();
$sid = (int) $_POST['service_id'];
$eid = (int) $_POST['employee_id'];
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$addr = trim($_POST['address'] ?? '');
$desc = trim($_POST['description'] ?? '');
$method = ($_POST['method'] ?? '') === 'card' ? 'card' : 'cash';

$service = db_one("SELECT * FROM services WHERE id=? AND is_active=1", [$sid]);
$emp = db_one("SELECT u.* FROM users u JOIN employee_services es ON es.employee_id=u.id WHERE u.id=? AND es.service_id=? AND u.role='employee' AND u.status='active' AND u.is_available=1", [$eid, $sid]);
$back = 'customer/book_service.php?service_id=' . $sid . '&employee_id=' . $eid;
$err = '';
if (!$service || !$emp)
    $err = 'That service or employee is not available.';
elseif (!strtotime($date) || $date < date('Y-m-d'))
    $err = 'Please choose a date that is today or later.';
elseif (!preg_match('/^\d{2}:\d{2}$/', $time))
    $err = 'Please choose a time.';
elseif ($date === date('Y-m-d') && $time <= date('H:i'))
    $err = 'That time has already passed.';
elseif ($addr === '' || $desc === '')
    $err = 'Please fill in the address and the work needed.';
elseif (!works_on($emp, $date))
    $err = $emp['name'] . ' does not work on that day. Please pick another date.';
elseif (!employee_free($eid, $date, $time))
    $err = $emp['name'] . ' already has a job around that time. Please pick another time.';
if ($err) {
    flash($err, 'danger');
    redirect($back);
}

$photo = save_upload('photo', 'uploads');
$bid = db_exec(
    "INSERT INTO bookings (customer_id,employee_id,service_id,booking_date,booking_time,address,description,photo,price,status) VALUES (?,?,?,?,?,?,?,?,?,'Pending')",
    [$u['id'], $eid, $sid, $date, $time, $addr, $desc, $photo, $service['price']]
);
db_exec("INSERT INTO payments (booking_id,amount,method,status) VALUES (?,?,?,'Unpaid')", [$bid, $service['price'], $method]);
redirect('customer/booking_confirmation.php?id=' . $bid);
