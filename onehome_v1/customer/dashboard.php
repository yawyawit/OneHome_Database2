<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'Overview';
$services = db_all("SELECT * FROM services WHERE is_active=1 ORDER BY name LIMIT 4");
$upcoming = list_bookings("b.customer_id=? AND b.status IN ('Pending','Confirmed','Ongoing')", [$u['id']], 'b.booking_date, b.booking_time');
$toPay = db_val("SELECT COUNT(*) FROM bookings b JOIN payments p ON p.booking_id=b.id WHERE b.customer_id=? AND b.status='Completed' AND p.status='Unpaid'", [$u['id']]);
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">CUSTOMER WORKSPACE</p>
        <h1>Welcome back, <?= e($u['name']) ?></h1>
        <p>Find a service, book a trusted provider, and follow your appointment.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('customer/services.php') ?>"><i class="bi bi-plus-lg me-1"></i>Book a service</a>
</div>

<form class="search-panel mb-4" method="get" action="<?= url('customer/services.php') ?>">
    <i class="bi bi-search"></i>
    <input class="form-control" name="q" placeholder="What service do you need? For example, plumbing or cleaning">
    <button class="btn btn-primary" type="submit">Search services</button>
</form>

<?php if ($toPay): ?>
    <div class="alert alert-warning d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="bi bi-credit-card me-2"></i><strong><?= number_format($toPay) ?> completed service<?= $toPay === 1 ? '' : 's' ?></strong> waiting for payment.</span>
        <a class="btn btn-sm btn-warning" href="<?= url('customer/bookings.php?status=Completed') ?>">View bookings</a>
    </div>
<?php endif; ?>

<div class="d-flex align-items-center justify-content-between gap-3 mb-3">
    <div><h3 class="mb-1">Upcoming bookings</h3><p class="small text-muted mb-0">Your active service requests and appointments.</p></div>
    <a class="btn btn-sm btn-outline-primary" href="<?= url('customer/bookings.php') ?>">View all</a>
</div>
<div class="card mb-4">
    <?php if ($upcoming): ?>
        <div class="list-group list-group-flush">
            <?php foreach (array_slice($upcoming, 0, 4) as $b): ?>
                <div class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="booking-date-icon"><strong><?= date('d', strtotime($b['booking_date'])) ?></strong><small><?= date('M', strtotime($b['booking_date'])) ?></small></span>
                        <div><strong><?= e($b['sname']) ?></strong><div class="small text-muted">with <?= e($b['ename']) ?> &middot; <?= ftime($b['booking_time']) ?></div></div>
                    </div>
                    <div class="d-flex align-items-center gap-2"><?= badge($b['status']) ?><a class="btn btn-sm btn-light" href="<?= url('customer/booking_details.php?id=' . $b['id']) ?>">Details</a></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state"><i class="bi bi-calendar2-plus"></i><strong>No upcoming bookings</strong><span>When you book a service, your appointment will appear here.</span><a class="btn btn-sm btn-primary mt-2" href="<?= url('customer/services.php') ?>">Browse services</a></div>
    <?php endif; ?>
</div>

<div class="d-flex align-items-center justify-content-between gap-3 mb-3">
    <div><h3 class="mb-1">Popular services</h3><p class="small text-muted mb-0">Choose a service to see available providers.</p></div>
    <a class="btn btn-sm btn-outline-primary" href="<?= url('customer/services.php') ?>">All services</a>
</div>
<div class="row"><?php foreach ($services as $s) echo service_card($s); ?></div>
<?php require INC . '/footer.php'; ?>
