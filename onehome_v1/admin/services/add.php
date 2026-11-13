<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name === '' || $_POST['price'] < 0) {
        flash('Please fill in the name and a valid price.', 'danger');
        redirect('admin/services/add.php');
    }
    db_exec("INSERT INTO services (name,description,price,icon) VALUES (?,?,?,?)", [$name, trim($_POST['description']), $_POST['price'], $_POST['icon']]);
    flash('Service added.');
    redirect('admin/services/index.php');
}
$title = 'Add Service';
require INC . '/header.php'; ?>
<h2>Add Service</h2>
<div style="max-width:600px"><?php service_form(); ?></div>
<?php require INC . '/footer.php'; ?>