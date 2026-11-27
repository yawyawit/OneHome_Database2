<?php
require_once __DIR__ . '/includes/auth.php';
$u = current_user();
if ($u && $u['status'] === 'active')
    redirect(home_for($u['role']));
$title = 'Home';
$services = db_all("SELECT * FROM services WHERE is_active=1 ORDER BY name");
require INC . '/header.php'; ?>
<div class="hero text-center p-5 mb-4 rounded">
    <h1>Welcome to One Home</h1>
    <p class="lead">Trusted home services in Baguio City &mdash; plumbing, electrical, cleaning and more.</p>
    <a class="btn btn-light btn-lg" href="<?= url('register.php') ?>">Get Started</a>
    <a class="btn btn-outline-light btn-lg" href="<?= url('login.php') ?>">Login</a>
</div>
<h3 class="mb-3">Our Services</h3>
<div class="row"><?php foreach ($services as $s)
    echo service_card($s, false); ?></div>
<?php require INC . '/footer.php'; ?>