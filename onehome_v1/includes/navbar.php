<?php
$u = current_user();
$role = $u['role'] ?? '';
$current_path = str_replace('\\', '/', parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '');
$base_path = rtrim(BASE_URL, '/');
$is_active = function ($path) use ($current_path, $base_path) {
    $full_path = $base_path . '/' . ltrim($path, '/');
    return $current_path === $full_path || strpos($current_path, $full_path . '/') === 0;
};
?>
<nav class="navbar app-topbar">
    <div class="container-fluid app-topbar-inner">
        <div class="d-flex align-items-center gap-2">
            <?php if ($u && $role === 'admin'): ?>
                <button class="btn btn-icon mobile-menu-button" id="sidebarToggle" type="button" aria-label="Open menu" aria-controls="adminSidebar" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
            <?php endif; ?>
            <a class="navbar-brand app-brand" href="<?= url($u ? home_for($role) : 'index.php') ?>">
                <span class="brand-mark"><i class="bi bi-house-heart-fill"></i></span>
                <span>One Home</span>
            </a>
        </div>

        <?php if ($u && $u['status'] === 'active' && $role !== 'admin'): ?>
            <div class="collapse d-lg-flex align-items-center app-main-links flex-grow-1" id="roleNavigation">
                <?php if ($role === 'customer'): ?>
                    <a class="top-link <?= $is_active('customer/dashboard.php') ? 'active' : '' ?>" href="<?= url('customer/dashboard.php') ?>">Overview</a>
                    <a class="top-link <?= $is_active('customer/services.php') || $is_active('customer/service_details.php') ? 'active' : '' ?>" href="<?= url('customer/services.php') ?>">Services</a>
                    <a class="top-link <?= $is_active('customer/bookings.php') || $is_active('customer/booking_details.php') ? 'active' : '' ?>" href="<?= url('customer/bookings.php') ?>">My Bookings</a>
                    <div class="dropdown">
                        <button class="top-link dropdown-toggle" type="button" data-bs-toggle="dropdown">More</button>
                        <ul class="dropdown-menu dropdown-menu-end app-dropdown">
                            <li><a class="dropdown-item" href="<?= url('customer/payment_history.php') ?>"><i class="bi bi-credit-card me-2"></i>Payment History</a></li>
                            <li><a class="dropdown-item" href="<?= url('customer/my_reviews.php') ?>"><i class="bi bi-star me-2"></i>My Reviews</a></li>
                            <li><a class="dropdown-item" href="<?= url('customer/profile.php') ?>"><i class="bi bi-person me-2"></i>My Profile</a></li>
                        </ul>
                    </div>
                <?php elseif ($role === 'employee'): ?>
                    <a class="top-link <?= $is_active('employee/dashboard.php') ? 'active' : '' ?>" href="<?= url('employee/dashboard.php') ?>">Overview</a>
                    <a class="top-link <?= $is_active('employee/booking_requests.php') ? 'active' : '' ?>" href="<?= url('employee/booking_requests.php') ?>">Requests<?php $request_count = new_requests_count($u['id']); if ($request_count): ?> <span class="nav-count"><?= $request_count ?></span><?php endif; ?></a>
                    <a class="top-link <?= $is_active('employee/my_jobs.php') || $is_active('employee/booking_details.php') ? 'active' : '' ?>" href="<?= url('employee/my_jobs.php') ?>">My Jobs</a>
                    <div class="dropdown">
                        <button class="top-link dropdown-toggle" type="button" data-bs-toggle="dropdown">More</button>
                        <ul class="dropdown-menu dropdown-menu-end app-dropdown">
                            <li><a class="dropdown-item" href="<?= url('employee/my_schedule.php') ?>"><i class="bi bi-calendar3 me-2"></i>Schedule</a></li>
                            <li><a class="dropdown-item" href="<?= url('employee/earnings.php') ?>"><i class="bi bi-wallet2 me-2"></i>Earnings</a></li>
                            <li><a class="dropdown-item" href="<?= url('employee/my_reviews.php') ?>"><i class="bi bi-star me-2"></i>Reviews</a></li>
                            <li><a class="dropdown-item" href="<?= url('employee/profile.php') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center gap-2 ms-auto">
            <?php if ($u && $u['status'] === 'active' && $role !== 'admin'): ?>
                <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#roleNavigation" aria-controls="roleNavigation" aria-expanded="false" aria-label="Open navigation">
                    <i class="bi bi-list"></i>
                </button>
            <?php endif; ?>
            <?php if ($u && $u['status'] === 'active'): ?>
                <div class="user-chip d-none d-sm-flex">
                    <span class="user-avatar"><?= e(strtoupper(substr($u['name'], 0, 1))) ?></span>
                    <span class="user-chip-name"><?= e($u['name']) ?></span>
                    <span class="user-chip-role"><?= e(ucfirst($role)) ?></span>
                </div>
                <a class="btn btn-sm btn-outline-secondary logout-button" href="<?= url('logout.php') ?>"><i class="bi bi-box-arrow-right me-1"></i><span class="d-none d-sm-inline">Sign out</span></a>
            <?php else: ?>
                <a class="top-link" href="<?= url('login.php') ?>">Sign in</a>
                <a class="btn btn-primary btn-sm px-3" href="<?= url('register.php') ?>">Create account</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if ($u && $u['status'] === 'active' && $role === 'admin'): ?>
    <div class="admin-layout">
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Administrator navigation">
            <div class="sidebar-caption">WORKSPACE</div>
            <a class="sidebar-link <?= $is_active('admin/dashboard.php') ? 'active' : '' ?>" href="<?= url('admin/dashboard.php') ?>">
                <i class="bi bi-grid-1x2"></i><span>Overview</span>
            </a>

            <div class="sidebar-caption">OPERATIONS</div>
            <a class="sidebar-link <?= $is_active('admin/bookings/') ? 'active' : '' ?>" href="<?= url('admin/bookings/index.php') ?>">
                <i class="bi bi-calendar2-check"></i><span>Bookings</span>
            </a>
            <a class="sidebar-link <?= $is_active('admin/services/') ? 'active' : '' ?>" href="<?= url('admin/services/index.php') ?>">
                <i class="bi bi-house-gear"></i><span>Services</span>
            </a>

            <div class="sidebar-caption">PEOPLE</div>
            <a class="sidebar-link <?= $is_active('admin/users/') ? 'active' : '' ?>" href="<?= url('admin/users/index.php') ?>">
                <i class="bi bi-people"></i><span>Users</span>
                <?php $pending_count = pending_employees_count(); if ($pending_count): ?><span class="sidebar-count"><?= $pending_count ?></span><?php endif; ?>
            </a>

            <div class="sidebar-caption">FINANCE</div>
            <a class="sidebar-link <?= $is_active('admin/payments/') ? 'active' : '' ?>" href="<?= url('admin/payments/index.php') ?>">
                <i class="bi bi-wallet2"></i><span>Payments</span>
            </a>
            <a class="sidebar-link <?= $is_active('admin/reports/') ? 'active' : '' ?>" href="<?= url('admin/reports/index.php') ?>">
                <i class="bi bi-graph-up"></i><span>Reports</span>
            </a>

            <div class="sidebar-caption">FEEDBACK</div>
            <a class="sidebar-link <?= $is_active('admin/reviews/') ? 'active' : '' ?>" href="<?= url('admin/reviews/index.php') ?>">
                <i class="bi bi-chat-square-text"></i><span>Reviews</span>
            </a>

            <div class="sidebar-spacer"></div>
            <div class="sidebar-divider"></div>
            <a class="sidebar-link <?= $is_active('admin/profile.php') ? 'active' : '' ?>" href="<?= url('admin/profile.php') ?>">
                <i class="bi bi-gear"></i><span>Account settings</span>
            </a>
            <div class="sidebar-user">
                <span class="user-avatar"><?= e(strtoupper(substr($u['name'], 0, 1))) ?></span>
                <span><strong><?= e($u['name']) ?></strong><small>System administrator</small></span>
            </div>
        </aside>
        <button class="sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Close menu"></button>
        <div class="admin-content">
<?php endif; ?>
