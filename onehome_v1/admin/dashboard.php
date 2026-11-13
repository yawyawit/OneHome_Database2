<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');

$u = current_user();
$title = 'Overview';

// Keep the overview focused on the numbers an administrator checks most often.
$cust = db_val("SELECT COUNT(*) FROM users WHERE role='customer' AND status='active'");
$emp = db_val("SELECT COUNT(*) FROM users WHERE role='employee' AND status='active'");
$total = db_val("SELECT COUNT(*) FROM bookings");
$month_paid = db_val("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status='Paid' AND MONTH(paid_at)=MONTH(CURDATE()) AND YEAR(paid_at)=YEAR(CURDATE())");

$pending_employees = pending_employees_count();
$pending_bookings = db_val("SELECT COUNT(*) FROM bookings WHERE status='Pending'");
$unpaid_completed = db_val("SELECT COUNT(*) FROM bookings b JOIN payments p ON p.booking_id=b.id WHERE b.status='Completed' AND p.status='Unpaid'");
$recent = db_all(
    "SELECT b.id, b.booking_date, b.booking_time, b.status,
            s.name AS sname, c.name AS cname, e.name AS ename
     FROM bookings b
     JOIN services s ON s.id = b.service_id
     JOIN users c ON c.id = b.customer_id
     JOIN users e ON e.id = b.employee_id
     ORDER BY b.id DESC
     LIMIT 6"
);

require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">ADMINISTRATOR WORKSPACE</p>
        <h1>Welcome back, <?= e($u['name']) ?></h1>
        <p>Here is a clear overview of what is happening in One Home.</p>
    </div>
    <div class="text-md-end small text-muted pt-1">
        <i class="bi bi-calendar3 me-1"></i><?= date('l, F j, Y') ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <?= stat_card('All bookings', number_format($total), 'bi-calendar2-check', 'primary') ?>
    <?= stat_card('Active customers', number_format($cust), 'bi-people', 'info') ?>
    <?= stat_card('Active employees', number_format($emp), 'bi-person-workspace', 'success') ?>
    <?= stat_card('Payments this month', money($month_paid), 'bi-wallet2', 'warning') ?>
</div>

<section class="mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h3 class="mb-1">Needs your attention</h3>
            <p class="text-muted small mb-0">Only items that may need an administrator action are shown here.</p>
        </div>
        <?php if (!$pending_employees && !$pending_bookings && !$unpaid_completed): ?>
            <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>All clear</span>
        <?php endif; ?>
    </div>

    <?php if ($pending_employees || $pending_bookings || $unpaid_completed): ?>
        <div class="row g-3">
            <?php if ($pending_employees): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-start gap-3">
                            <span class="stat-icon stat-icon-warning"><i class="bi bi-person-check"></i></span>
                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1"><?= number_format($pending_employees) ?> employee account<?= $pending_employees === 1 ? '' : 's' ?></div>
                                <p class="small text-muted mb-3">Waiting for administrator approval.</p>
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('admin/users/index.php?role=employee') ?>">Review employees</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($pending_bookings): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-start gap-3">
                            <span class="stat-icon stat-icon-info"><i class="bi bi-calendar2-event"></i></span>
                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1"><?= number_format($pending_bookings) ?> pending booking<?= $pending_bookings === 1 ? '' : 's' ?></div>
                                <p class="small text-muted mb-3">Check the latest booking requests.</p>
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('admin/bookings/index.php?status=Pending') ?>">View bookings</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($unpaid_completed): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-start gap-3">
                            <span class="stat-icon stat-icon-danger"><i class="bi bi-cash-coin"></i></span>
                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1"><?= number_format($unpaid_completed) ?> completed job<?= $unpaid_completed === 1 ? '' : 's' ?> unpaid</div>
                                <p class="small text-muted mb-3">Check payment records for completed jobs.</p>
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('admin/payments/index.php?status=Unpaid') ?>">Review payments</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-icon stat-icon-success"><i class="bi bi-check2-circle"></i></span>
                <div>
                    <div class="fw-bold">You are all caught up.</div>
                    <p class="small text-muted mb-0">There are no pending approvals, booking requests, or completed jobs awaiting payment.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<section>
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h3 class="mb-1">Recent bookings</h3>
            <p class="text-muted small mb-0">The six most recent bookings across the system.</p>
        </div>
        <a class="btn btn-sm btn-outline-primary" href="<?= url('admin/bookings/index.php') ?>">View all bookings</a>
    </div>

    <div class="table-responsive">
        <?php if ($recent): ?>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Employee</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $b): ?>
                        <tr>
                            <td><strong>#<?= (int) $b['id'] ?></strong><br><span class="small text-muted"><?= e($b['sname']) ?></span></td>
                            <td><?= e($b['cname']) ?></td>
                            <td><?= e($b['ename']) ?></td>
                            <td><?= fdate($b['booking_date']) ?><br><span class="small text-muted"><?= ftime($b['booking_time']) ?></span></td>
                            <td><?= badge($b['status']) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-light" href="<?= url('admin/bookings/view.php?id=' . $b['id']) ?>">Details <i class="bi bi-arrow-right ms-1"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-calendar2-x"></i><strong>No bookings yet</strong><span>New customer bookings will appear here.</span></div>
        <?php endif; ?>
    </div>
</section>
<?php require INC . '/footer.php'; ?>
