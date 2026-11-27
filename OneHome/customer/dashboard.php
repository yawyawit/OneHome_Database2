<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'Home';
$services = db_all("SELECT * FROM services WHERE is_active=1 ORDER BY name LIMIT 4");
$upcoming = list_bookings("b.customer_id=? AND b.status IN ('Pending','Confirmed','Ongoing')", [$u['id']], 'b.booking_date, b.booking_time');
$toPay = db_val("SELECT COUNT(*) FROM bookings b JOIN payments p ON p.booking_id=b.id WHERE b.customer_id=? AND b.status='Completed' AND p.status='Unpaid'", [$u['id']]);
require INC . '/header.php';
?>
<section class="hero-panel mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="text-uppercase small fw-bold text-success mb-2">Home services, made simple</div>
            <h2 class="mb-2">Hello, <?= e(explode(' ', $u['name'])[0]) ?>.</h2>
            <p class="text-muted mb-3">What can we help you with today?</p>
            <form class="input-group" method="get" action="<?= url('customer/services.php') ?>">
                <input class="form-control" name="q" placeholder="Search a service">
                <button class="btn btn-primary px-4"><i class="bi bi-search"></i> Find</button>
            </form>
        </div>
        <div class="col-lg-4 d-none d-lg-flex justify-content-end"><i class="bi bi-house-heart" style="font-size:6rem;color:#55765d;opacity:.75"></i></div>
    </div>
</section>
<?php if ($toPay): ?>
    <a class="quick-link mb-4" href="<?= url('customer/bookings.php?status=Completed') ?>"><i class="bi bi-wallet2"></i><span><?= $toPay ?> completed service(s) need payment</span><i class="bi bi-arrow-right ms-auto"></i></a>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center mb-2"><h4 class="mb-0">Your upcoming bookings</h4><a class="small text-decoration-none" href="<?= url('customer/bookings.php') ?>">All bookings</a></div>
<?php if (!$upcoming): ?>
    <div class="empty-state mb-4"><i class="bi bi-calendar2-check fs-3 d-block mb-2"></i>No upcoming bookings yet.</div>
<?php else: ?>
    <div class="row g-3 mb-4">
        <?php foreach (array_slice($upcoming, 0, 3) as $b): ?>
            <div class="col-md-4"><div class="card h-100"><div class="card-body">
                <div class="d-flex justify-content-between mb-2"><?= badge($b['status']) ?><small class="text-muted"><?= fdate($b['booking_date']) ?></small></div>
                <h5><?= e($b['sname']) ?></h5><p class="small text-muted mb-3">With <?= e($b['ename']) ?> · <?= ftime($b['booking_time']) ?></p>
                <a class="btn btn-sm btn-outline-primary" href="<?= url('customer/booking_details.php?id=' . $b['id']) ?>">Details</a>
            </div></div></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center mb-2"><h4 class="mb-0">Popular services</h4><a class="small text-decoration-none" href="<?= url('customer/services.php') ?>">Browse all</a></div>
<div class="row"><?php foreach ($services as $service) echo service_card($service); ?></div>
<?php require INC . '/footer.php'; ?>
