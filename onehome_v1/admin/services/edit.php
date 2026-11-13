<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$s = db_one("SELECT * FROM services WHERE id=?", [(int) ($_GET['id'] ?? 0)]);
if (!$s) {
    flash('Service not found.', 'danger');
    redirect('admin/services/index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name === '' || !is_numeric($_POST['price']) || (float) $_POST['price'] < 0) {
        flash('Please fill in the name and a valid price.', 'danger');
        redirect('admin/services/edit.php?id=' . $s['id']);
    }
    db_exec("UPDATE services SET name=?, description=?, price=?, icon=?, is_active=? WHERE id=?", [$name, trim($_POST['description']), $_POST['price'], $_POST['icon'], isset($_POST['is_active']) ? 1 : 0, $s['id']]);
    flash('Service updated.');
    redirect('admin/services/index.php');
}
$title = 'Edit Service';
require INC . '/header.php'; ?>
<h2>Edit Service</h2>
<div style="max-width:600px"><?php service_form($s); ?></div>
<?php require INC . '/footer.php'; ?>