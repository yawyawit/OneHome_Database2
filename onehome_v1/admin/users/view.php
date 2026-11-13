<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$x = db_one("SELECT * FROM users WHERE id=? AND role<>'admin'", [(int) ($_GET['id'] ?? 0)]);
if (!$x) {
    flash('User not found.', 'danger');
    redirect('admin/users/index.php');
}
$title = 'User Details';
$col = $x['role'] === 'customer' ? 'customer_id' : 'employee_id';
$rows = list_bookings("b.$col=?", [$x['id']]);
$svc = db_all("SELECT s.name FROM employee_services es JOIN services s ON s.id=es.service_id WHERE es.employee_id=?", [$x['id']]);
require INC . '/header.php'; ?>
<h2><?= e($x['name']) ?> <?= badge($x['status']) ?></h2>
<table class="table table-bordered bg-white" style="max-width:700px">
    <tr>
        <th width="30%">Role</th>
        <td><?= e(ucfirst($x['role'])) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= e($x['email']) ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?= e($x['phone']) ?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td><?= e($x['address']) ?></td>
    </tr>
    <tr>
        <th>Joined</th>
        <td><?= fdate($x['created_at']) ?></td>
    </tr>
    <?php if ($x['role'] === 'employee'): ?>
        <tr>
            <th>Skills</th>
            <td><?= e($x['skills']) ?></td>
        </tr>
        <tr>
            <th>Services</th>
            <td><?= e(implode(', ', array_column($svc, 'name'))) ?></td>
        </tr>
        <tr>
            <th>Working days</th>
            <td><?= e($x['available_days']) ?></td>
        </tr><?php endif; ?>
</table>
<h4>Bookings (<?= count($rows) ?>)</h4>
<?php bookings_table($rows, 'admin'); ?>
<a class="btn btn-secondary" href="index.php?role=<?= $x['role'] ?>">Back</a>
<?php require INC . '/footer.php'; ?>