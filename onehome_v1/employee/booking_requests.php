<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'Booking Requests';
$rows = list_bookings("b.employee_id=? AND b.status='Pending'", [$u['id']], 'b.booking_date, b.booking_time');
require INC . '/header.php'; ?>
<h2>New Booking Requests</h2>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No new requests right now.</div><?php endif; ?>
<?php foreach ($rows as $b):
    $free = employee_free($u['id'], $b['booking_date'], $b['booking_time']); ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5><?= e($b['sname']) ?> <small class="text-muted">&mdash; Booking #<?= $b['id'] ?></small></h5>
            <p class="mb-1"><b>Customer:</b> <?= e($b['cname']) ?> (<?= e($b['cphone']) ?>)</p>
            <p class="mb-1"><b>When:</b> <?= fdate($b['booking_date']) ?> at <?= ftime($b['booking_time']) ?></p>
            <p class="mb-1"><b>Where:</b> <?= e($b['address']) ?></p>
            <p class="mb-2"><b>Job details:</b> <?= e($b['description']) ?></p>
            <?php if (!$free): ?>
                <div class="alert alert-danger py-1">You already have a job around this time. You cannot accept it.</div>
            <?php endif; ?>
            <?= job_buttons($b) ?> <a class="btn btn-sm btn-outline-primary"
                href="<?= url('employee/booking_details.php?id=' . $b['id']) ?>">Details</a>
        </div>
    </div>
<?php endforeach; ?>
<?php require INC . '/footer.php'; ?>