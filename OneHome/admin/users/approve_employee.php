<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
post_only();
db_exec("UPDATE users SET status='active' WHERE id=? AND role='employee' AND status='pending'", [(int) $_POST['id']]);
flash('Employee approved. They can log in now.');
redirect('admin/users/index.php?role=employee');
