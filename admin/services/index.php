<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Manage Services';
$rows = db_all("SELECT s.*, (SELECT COUNT(*) FROM bookings b WHERE b.service_id=s.id) bookings FROM services s ORDER BY s.name");
require INC . '/header.php'; ?>
<div class="d-flex justify-content-between">
    <h2>Manage Services</h2>
    <div><a class="btn btn-primary" href="add.php"><i class="bi bi-plus-lg"></i> Add Service</a></div>
</div>
<table class="table table-striped bg-white align-middle mt-3">
    <thead class="table-light">
        <tr>
            <th></th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Bookings</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $s): ?>
            <tr>
                <td><i class="bi <?= e($s['icon']) ?> fs-4"></i></td>
                <td><?= e($s['name']) ?></td>
                <td class="small"><?= e($s['description']) ?></td>
                <td><?= money($s['price']) ?></td>
                <td><?= $s['bookings'] ?></td>
                <td><?= $s['is_active'] ? badge('active') : badge('inactive') ?></td>
                <td><a class="btn btn-sm btn-outline-primary" href="edit.php?id=<?= $s['id'] ?>">Edit</a>
                    <form method="post" action="delete.php" class="d-inline" data-confirm="Remove this service?"><input
                            type="hidden" name="id" value="<?= $s['id'] ?>"><button
                            class="btn btn-sm btn-danger">Remove</button></form>
                </td>
            </tr><?php endforeach; ?>
    </tbody>
</table>
<p class="small text-muted">Services that already have bookings are hidden (set inactive) instead of deleted, so old
    records stay correct.</p>
<?php require INC . '/footer.php'; ?>