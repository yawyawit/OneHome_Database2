<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Services';
$rows = db_all("SELECT s.*, (SELECT COUNT(*) FROM bookings b WHERE b.service_id=s.id) bookings FROM services s ORDER BY s.name");
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">OPERATIONS</p>
        <h1>Services</h1>
        <p>Manage the home services customers can request.</p>
    </div>
    <a class="btn btn-primary" href="add.php"><i class="bi bi-plus-lg me-1"></i>Add service</a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Service catalogue</span>
        <span class="small text-muted"><?= count($rows) ?> service<?= count($rows) === 1 ? '' : 's' ?></span>
    </div>
    <div class="table-responsive border-0">
        <?php if ($rows): ?>
            <table class="table table-hover align-middle">
                <thead><tr><th>Service</th><th>Description</th><th>Starting price</th><th>Bookings</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($rows as $s): ?>
                        <tr>
                            <td><span class="d-inline-flex align-items-center gap-2 fw-semibold"><span class="service-table-icon"><i class="bi <?= e($s['icon']) ?>"></i></span><?= e($s['name']) ?></span></td>
                            <td class="small text-muted service-description"><?= e($s['description']) ?></td>
                            <td class="fw-semibold text-nowrap"><?= money($s['price']) ?></td>
                            <td><?= number_format($s['bookings']) ?></td>
                            <td><?= $s['is_active'] ? badge('active') : badge('inactive') ?></td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-light" href="edit.php?id=<?= $s['id'] ?>">Edit</a>
                                <form method="post" action="delete.php" class="d-inline" data-confirm="Remove this service?"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button class="btn btn-sm btn-outline-danger">Remove</button></form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-house-gear"></i><strong>No services added yet</strong><span>Add a service to make it available to customers.</span></div>
        <?php endif; ?>
    </div>
</div>
<p class="small text-muted mt-3"><i class="bi bi-info-circle me-1"></i>Services with existing bookings are deactivated instead of permanently deleted, so booking history remains accurate.</p>
<?php require INC . '/footer.php'; ?>
