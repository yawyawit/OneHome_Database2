<?php require_once __DIR__ . '/../includes/auth.php';
require_role('employee');
$u = current_user();
$title = 'My Schedule';
$rows = list_bookings("b.employee_id=? AND b.status IN ('Confirmed','Ongoing')", [$u['id']], 'b.booking_date, b.booking_time');
require INC . '/header.php'; ?>
<h2>My Schedule</h2>
<p>Working days: <b><?= e(str_replace(',', ', ', $u['available_days'])) ?></b> &middot; Accepting new bookings:
    <?= $u['is_available'] ? badge('active') : badge('inactive') ?>
    <a href="<?= url('employee/availability.php') ?>">Manage availability</a>
</p>
<?php if (!$rows): ?>
    <div class="alert alert-secondary">No upcoming jobs.</div><?php else: ?>
    <table class="table table-striped bg-white">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Service</th>
                <th>Customer</th>
                <th>Location</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $b): ?>
                <tr class="<?= $b['booking_date'] === date('Y-m-d') ? 'table-warning' : '' ?>">
                    <td><?= fdate($b['booking_date']) ?><?= $b['booking_date'] === date('Y-m-d') ? ' <b>(Today)</b>' : '' ?>
                    </td>
                    <td><?= ftime($b['booking_time']) ?></td>
                    <td><?= e($b['sname']) ?></td>
                    <td><?= e($b['cname']) ?></td>
                    <td><?= e($b['address']) ?></td>
                    <td><?= badge($b['status']) ?></td>
                    <td><a class="btn btn-sm btn-outline-primary"
                            href="<?= url('employee/booking_details.php?id=' . $b['id']) ?>">Details</a></td>
                </tr><?php endforeach; ?>
        </tbody>
    </table><?php endif; ?>
<?php require INC . '/footer.php'; ?>