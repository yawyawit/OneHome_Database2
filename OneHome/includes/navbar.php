<?php
$links = [];
if ($u && $u['status'] === 'active') {
    if ($u['role'] === 'customer') {
        $links = [
            ['url' => 'customer/dashboard.php', 'label' => 'Home', 'icon' => 'bi-house'],
            ['url' => 'customer/services.php', 'label' => 'Services', 'icon' => 'bi-grid'],
            ['url' => 'customer/bookings.php', 'label' => 'Bookings', 'icon' => 'bi-calendar-check'],
            ['url' => 'customer/payment_history.php', 'label' => 'Payments', 'icon' => 'bi-wallet2'],
            ['url' => 'customer/my_reviews.php', 'label' => 'Reviews', 'icon' => 'bi-star'],
        ];
    } elseif ($u['role'] === 'employee') {
        $links = [
            ['url' => 'employee/dashboard.php', 'label' => 'Home', 'icon' => 'bi-house'],
            ['url' => 'employee/booking_requests.php', 'label' => 'Requests', 'icon' => 'bi-inbox', 'count' => new_requests_count($u['id'])],
            ['url' => 'employee/my_jobs.php', 'label' => 'My Jobs', 'icon' => 'bi-tools'],
            ['url' => 'employee/my_schedule.php', 'label' => 'Schedule', 'icon' => 'bi-calendar3'],
            ['url' => 'employee/earnings.php', 'label' => 'Earnings', 'icon' => 'bi-cash'],
            ['url' => 'employee/my_reviews.php', 'label' => 'Reviews', 'icon' => 'bi-star'],
        ];
    } else {
        $links = [
            ['url' => 'admin/dashboard.php', 'label' => 'Overview', 'icon' => 'bi-speedometer2'],
            ['url' => 'admin/bookings/index.php', 'label' => 'Bookings', 'icon' => 'bi-calendar-check'],
            ['url' => 'admin/users/index.php', 'label' => 'People', 'icon' => 'bi-people', 'count' => pending_employees_count()],
            ['url' => 'admin/services/index.php', 'label' => 'Services', 'icon' => 'bi-grid'],
            ['url' => 'admin/payments/index.php', 'label' => 'Payments', 'icon' => 'bi-wallet2'],
            ['url' => 'admin/reviews/index.php', 'label' => 'Reviews', 'icon' => 'bi-star'],
            ['url' => 'admin/reports/index.php', 'label' => 'Reports', 'icon' => 'bi-graph-up'],
        ];
    }
}
$current_path = str_replace('\\\\', '/', $_SERVER['PHP_SELF'] ?? '');
?>
<header class="app-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= url($u && $u['status'] === 'active' ? home_for($u['role']) : 'index.php') ?>">
                <span class="brand-mark"><i class="bi bi-house-heart-fill"></i></span>
                <span>One Home</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <?php foreach ($links as $item):
                        $active = strpos($current_path, '/' . $item['url']) !== false;
                        $count = (int) ($item['count'] ?? 0);
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $active ? 'active' : '' ?>" href="<?= url($item['url']) ?>">
                                <i class="bi <?= e($item['icon']) ?>"></i>
                                <?= e($item['label']) ?>
                                <?php if ($count): ?><span class="nav-count"><?= $count ?></span><?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="nav-account">
                    <?php if ($u && $u['status'] === 'active'): ?>
                        <a class="nav-profile" href="<?= url($u['role'] === 'admin' ? 'admin/profile.php' : $u['role'] . '/profile.php') ?>">
                            <span class="nav-avatar"><?= e(strtoupper(substr($u['name'], 0, 1))) ?></span>
                            <span class="d-none d-md-inline"><?= e($u['name']) ?></span>
                        </a>
                        <form method="post" action="<?= url('logout.php') ?>" class="d-inline">
                            <?= csrf_input() ?>
                            <button class="btn btn-logout" type="submit">Sign out</button>
                        </form>
                    <?php else: ?>
                        <a class="nav-link" href="<?= url('login.php') ?>">Login</a>
                        <a class="btn btn-primary btn-sm" href="<?= url('register.php') ?>">Create account</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
