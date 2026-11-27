<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
post_only();
$u = current_user();
$b = get_booking((int) $_POST['booking_id']);
$rating = (int) ($_POST['rating'] ?? 0);
if (!$b || $b['customer_id'] != $u['id'] || $b['status'] !== 'Completed') {
    flash('You can only review your own completed bookings.', 'danger');
    redirect('customer/bookings.php');
}
if ($b['reviewed']) {
    flash('You already reviewed this booking.', 'warning');
    redirect('customer/my_reviews.php');
}
if ($rating < 1 || $rating > 5) {
    flash('Please choose a rating from 1 to 5.', 'danger');
    redirect('customer/review.php?id=' . $b['id']);
}
db_exec("INSERT INTO reviews (booking_id,customer_id,employee_id,rating,comment) VALUES (?,?,?,?,?)", [$b['id'], $u['id'], $b['employee_id'], $rating, trim($_POST['comment'] ?? '')]);
flash('Thank you for your review!');
redirect('customer/my_reviews.php');
