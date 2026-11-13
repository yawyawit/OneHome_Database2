<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'Dashboard';
$services = db_all("SELECT * FROM services WHERE is_active=1 ORDER BY name LIMIT 4");
$upcoming = list_bookings("b.customer_id=? AND b.status IN ('Pending','Confirmed','Ongoing')", [$u['id']], 'b.booking_date, b.booking_time');
$toPay = db_val("SELECT COUNT(*) FROM bookings b JOIN payments p ON p.booking_id=b.id WHERE b.customer_id=? AND b.status='Completed' AND p.status='Unpaid'", [$u['id']]);
require INC . '/header.php'; ?>
<h2>Welcome back, <?= e($u['name']) ?>!</h2>
<form class="input-group my-3" method="get" action="<?= url('customer/services.php') ?>">
    <input class="form-control" name="q" placeholder="Search services (e.g. plumbing, cleaning)...">
    <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
</form>
<div class="mb-4">
    <a class="btn btn-outline-primary" href="<?= url('customer/bookings.php') ?>"><i class="bi bi-calendar-check"></i>
        My
        Bookings</a>
    <a class="btn btn-outline-primary" href="<?= url('customer/profile.php') ?>"><i class="bi bi-person"></i> My
        Profile</a>
    <a class="btn btn-outline-primary" href="<?= url('customer/services.php') ?>"><i class="bi bi-grid"></i> All
        Services</a>
</div>
<?php if ($toPay): ?>
    <div class="alert alert-warning">You have <b><?= $toPay ?></b> completed job(s) waiting for payment. <a
            href="<?= url('customer/bookings.php?status=Completed') ?>">View</a></div><?php endif; ?>
<div class="card mb-4">
    <div class="card-header">Upcoming Bookings (<?= count($upcoming) ?>)</div>
    <div class="card-body">
            <?php if (!$upcoming): ?>
            <p class="text-muted mb-0">No upcoming bookings yet. Pick a service below to get started!</p>
            <?php else: ?>
            <ul class="list-group"><?php foreach (array_slice($upcoming, 0, 3) as $b): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><b><?= e($b['sname']) ?></b> with <?= e($b['ename']) ?> &mdash; <?= fdate($b['booking_date']) ?>,
                            <?= ftime($b['booking_time']) ?></span>
                        <span><?= badge($b['status']) ?> <a class="btn btn-sm btn-outline-primary"
                                href="<?= url('customer/booking_details.php?id=' . $b['id']) ?>">View</a></span>
                    </li>
                        <?php endforeach; ?>
            </ul><?php endif; ?>
    </div>
</div>
<h4>Available Services</h4>
<div class="row"><?php foreach ($services as $s)
    echo service_card($s); ?></div>
<a href="<?= url('customer/services.php') ?>">See all services &raquo;</a>
<?php require INC . '/footer.php'; ?>