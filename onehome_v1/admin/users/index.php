<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Users';
$requested_role = $_GET['role'] ?? 'customer';
$role = in_array($requested_role, ['customer', 'employee', 'admin'], true) ? $requested_role : 'customer';
$current_admin = current_user();
$users = db_all("SELECT * FROM users WHERE role=? ORDER BY (status='pending') DESC, name", [$role]);
require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">PEOPLE</p>
        <h1>Users</h1>
        <p>Manage customer accounts, employee registrations, and administrator access.</p>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="d-flex flex-wrap gap-2">
    <a class="btn <?= $role === 'customer' ? 'btn-primary' : 'btn-outline-secondary' ?>" href="index.php?role=customer">Customers</a>
    <a class="btn <?= $role === 'employee' ? 'btn-primary' : 'btn-outline-secondary' ?>" href="index.php?role=employee">
        Employees<?php if (pending_employees_count()): ?> <span class="badge bg-light text-primary ms-1"><?= pending_employees_count() ?> pending</span><?php endif; ?>
    </a>
    <a class="btn <?= $role === 'admin' ? 'btn-primary' : 'btn-outline-secondary' ?>" href="index.php?role=admin">Administrators</a>
    </div>
    <?php if ($role === 'admin'): ?>
        <a class="btn btn-primary" href="create_admin.php"><i class="bi bi-person-plus me-1"></i>Add Administrator</a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><?= ucfirst($role) ?> accounts</span>
        <span class="text-muted small"><?= count($users) ?> account<?= count($users) === 1 ? '' : 's' ?></span>
    </div>
    <div class="table-responsive border-0">
        <?php if ($users): ?>
            <table class="table table-hover align-middle">
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($users as $x): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($x['name']) ?></td>
                            <td><?= e($x['email']) ?></td>
                            <td><?= e($x['phone']) ?></td>
                            <td><?= fdate($x['created_at']) ?></td>
                            <td><?= badge($x['status']) ?><?php if ($role === 'admin' && (int) $x['id'] === (int) $current_admin['id']): ?> <span class="badge bg-light text-secondary">You</span><?php endif; ?></td>
                            <td class="text-nowrap">
                                <?php if ($role !== 'admin'): ?>
                                    <a class="btn btn-sm btn-light" href="view.php?id=<?= $x['id'] ?>">View</a>
                                    <?php if ($x['status'] === 'pending'): ?>
                                    <form method="post" action="approve_employee.php" class="d-inline"><input type="hidden" name="id" value="<?= $x['id'] ?>"><button class="btn btn-sm btn-success">Approve</button></form>
                                <?php elseif ($x['status'] === 'active'): ?>
                                    <form method="post" action="update_status.php" class="d-inline" data-confirm="Deactivate this account?"><input type="hidden" name="id" value="<?= $x['id'] ?>"><input type="hidden" name="status" value="inactive"><button class="btn btn-sm btn-outline-danger">Deactivate</button></form>
                                    <?php else: ?>
                                        <form method="post" action="update_status.php" class="d-inline"><input type="hidden" name="id" value="<?= $x['id'] ?>"><input type="hidden" name="status" value="active"><button class="btn btn-sm btn-outline-success">Activate</button></form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted small">Managed by administrators</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-people"></i><strong>No <?= e($role) ?> accounts found</strong><span><?= $role === 'admin' ? 'Use Add Administrator to create another admin account.' : 'New accounts will appear here.' ?></span></div>
        <?php endif; ?>
    </div>
</div>
<?php require INC . '/footer.php'; ?>
