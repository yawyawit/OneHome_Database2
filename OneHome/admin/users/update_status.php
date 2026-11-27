<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
post_only();
$x = db_one("SELECT * FROM users WHERE id=? AND role<>'admin'", [(int) $_POST['id']]);   // admins cannot be deactivated here
$status = ($_POST['status'] ?? '') === 'inactive' ? 'inactive' : 'active';
if (!$x) {
    flash('User not found.', 'danger');
    redirect('admin/users/index.php');
}
db_exec("UPDATE users SET status=? WHERE id=?", [$status, $x['id']]);
flash('Account ' . ($status === 'active' ? 'activated.' : 'deactivated.'));
redirect('admin/users/index.php?role=' . $x['role']);
