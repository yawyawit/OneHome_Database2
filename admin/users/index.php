<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Manage Users';
$role = ($_GET['role'] ?? '') === 'employee' ? 'employee' : 'customer';
$users = db_all("SELECT * FROM users WHERE role=? ORDER BY (status='pending') DESC, name", [$role]);
require INC . '/header.php'; ?>
<h2>Manage Users</h2>
<div class="mb-3"><a class="btn btn-sm <?= $role === 'customer' ? 'btn-dark' : 'btn-outline-dark' ?>"
        href="index.php?role=customer">Customers</a>
    <a class="btn btn-sm <?= $role === 'employee' ? 'btn-dark' : 'btn-outline-dark' ?>"
        href="index.php?role=employee">Employees <?php if (pending_employees_count()): ?><span
                class="badge bg-danger"><?= pending_employees_count() ?> pending</span><?php endif; ?></a>
</div>
<table class="table table-striped bg-white align-middle">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Joined</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
            <?php foreach ($users as $x): ?>
            <tr>
                <td><?= e($x['name']) ?></td>
                <td><?= e($x['email']) ?></td>
                <td><?= e($x['phone']) ?></td>
                <td><?= fdate($x['created_at']) ?></td>
                <td><?= badge($x['status']) ?></td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="view.php?id=<?= $x['id'] ?>">View</a>
                              <?php if ($x['status'] === 'pending'): ?>
                        <form method="post" action="approve_employee.php" class="d-inline"><input type="hidden" name="id"
                                value="<?= $x['id'] ?>"><button class="btn btn-sm btn-success">Approve</button></form>
                              <?php elseif ($x['status'] === 'active'): ?>
                        <form method="post" action="update_status.php" class="d-inline" data-confirm="Deactivate this account?">
                            <input type="hidden" name="id" value="<?= $x['id'] ?>"><input type="hidden" name="status"
                                value="inactive"><button class="btn btn-sm btn-danger">Deactivate</button></form>
                              <?php else: ?>
                        <form method="post" action="update_status.php" class="d-inline"><input type="hidden" name="id"
                                value="<?= $x['id'] ?>"><input type="hidden" name="status" value="active"><button
                                class="btn btn-sm btn-success">Activate</button></form>
                              <?php endif; ?>
                </td>
            </tr><?php endforeach; ?>
    </tbody>
</table>
<?php require INC . '/footer.php'; ?>