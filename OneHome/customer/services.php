<?php require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$u = current_user();
$title = 'Services';
$q = trim($_GET['q'] ?? '');
$services = db_all("SELECT * FROM services WHERE is_active=1 AND (name LIKE ? OR description LIKE ?) ORDER BY name", ["%$q%", "%$q%"]);
require INC . '/header.php'; ?>
<h2>Browse Services</h2>
<form class="input-group my-3" method="get">
    <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search services...">
    <button class="btn btn-primary">Search</button>
</form>
<?php if (!$services): ?>
    <div class="alert alert-secondary">No services found<?= $q ? ' for "' . e($q) . '"' : '' ?>.</div><?php endif; ?>
<div class="row"><?php foreach ($services as $s)
    echo service_card($s); ?></div>
<?php require INC . '/footer.php'; ?>