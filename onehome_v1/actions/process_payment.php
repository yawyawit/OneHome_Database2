<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
post_only();
$u = current_user();
$b = get_booking((int) $_POST['booking_id']);
if (!$b || $b['customer_id'] != $u['id'] || $b['status'] !== 'Completed' || $b['pstatus'] !== 'Unpaid') {
    flash('You cannot pay for this booking.', 'danger');
    redirect('customer/bookings.php');
}
if (($_POST['action'] ?? '') === 'cash') {
    db_exec("UPDATE payments SET method='cash' WHERE booking_id=?", [$b['id']]);
    flash('Okay! Please pay your employee in cash. They will mark it as paid.', 'info');
    redirect('customer/payment.php?id=' . $b['id']);
}
// ---- Simulated card payment (DEMO ONLY: nothing is charged, full card number is never saved) ----
$num = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
$err = '';
if (trim($_POST['card_name'] ?? '') === '')
    $err = 'Please enter the name on the card.';
elseif (strlen($num) !== 16)
    $err = 'Card number must be 16 digits.';
elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $_POST['expiry'] ?? ''))
    $err = 'Expiry must look like MM/YY.';
elseif (!preg_match('/^\d{3,4}$/', $_POST['cvv'] ?? ''))
    $err = 'CVV must be 3 or 4 digits.';
if ($err) {
    flash($err, 'danger');
    redirect('customer/payment.php?id=' . $b['id'] . '&method=card');
}
db_exec("UPDATE payments SET status='Paid', method='card', card_last4=?, paid_at=NOW() WHERE booking_id=?", [substr($num, -4), $b['id']]);
redirect('customer/payment_success.php?id=' . $b['id']);
