<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= url($u ? home_for($u['role']) : 'index.php') ?>"><i
                class="bi bi-house-heart-fill"></i> One Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span
                class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <?php
                $links = [];
                if ($u && $u['status'] === 'active') {
                    if ($u['role'] === 'customer')
                        $links = ['customer/dashboard.php' => 'Home', 'customer/services.php' => 'Services', 'customer/bookings.php' => 'My Bookings', 'customer/payment_history.php' => 'Payments', 'customer/my_reviews.php' => 'My Reviews', 'customer/profile.php' => 'My Profile'];
                    if ($u['role'] === 'employee')
                        $links = ['employee/dashboard.php' => 'Dashboard', 'employee/booking_requests.php' => 'Requests', 'employee/my_schedule.php' => 'Schedule', 'employee/my_jobs.php' => 'My Jobs', 'employee/earnings.php' => 'Earnings', 'employee/my_reviews.php' => 'Reviews', 'employee/profile.php' => 'Profile'];
                    if ($u['role'] === 'admin')
                        $links = ['admin/dashboard.php' => 'Dashboard', 'admin/users/index.php' => 'Users', 'admin/services/index.php' => 'Services', 'admin/bookings/index.php' => 'Bookings', 'admin/payments/index.php' => 'Payments', 'admin/reviews/index.php' => 'Reviews', 'admin/reports/index.php' => 'Reports', 'admin/profile.php' => 'Profile'];
                }
                foreach ($links as $href => $label) {
                    $n = 0;
                    if ($href === 'employee/booking_requests.php')
                        $n = new_requests_count($u['id']);
                    if ($href === 'admin/users/index.php')
                        $n = pending_employees_count();
                    echo '<li class="nav-item"><a class="nav-link" href="' . url($href) . '">' . $label . ($n ? ' <span class="badge bg-danger">' . $n . '</span>' : '') . '</a></li>';
                }
                ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($u && $u['status'] === 'active'): ?>
                    <li class="nav-item"><span class="navbar-text me-3">Hi, <?= e($u['name']) ?>
                            (<?= e($u['role']) ?>)</span></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm" href="<?= url('logout.php') ?>">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('login.php') ?>">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('register.php') ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>