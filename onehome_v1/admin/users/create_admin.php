<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');

$title = 'Add Administrator';
$current_admin = current_user();

// A one-time token helps protect this form from unwanted submissions.
if (empty($_SESSION['create_admin_token'])) {
    $_SESSION['create_admin_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    post_only();

    $submitted_token = $_POST['token'] ?? '';
    if (!hash_equals($_SESSION['create_admin_token'], $submitted_token)) {
        flash('The form expired. Please try again.', 'danger');
        redirect('admin/users/create_admin.php');
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    $error = '';

    if ($name === '' || strlen($name) > 100) {
        $error = 'Please enter a name with no more than 100 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $error = 'Please enter a valid email address.';
    } elseif ($phone !== '' && !preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
        $error = 'Please enter a valid contact number.';
    } elseif (strlen($password) < 8) {
        $error = 'The password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'The passwords do not match.';
    } elseif (db_val("SELECT COUNT(*) FROM users WHERE email=?", [$email]) > 0) {
        $error = 'That email address is already in use.';
    }

    if ($error !== '') {
        flash($error, 'danger');
        redirect('admin/users/create_admin.php');
    }

    // The role is fixed here. It is never taken from a form field.
    db_exec(
        "INSERT INTO users (name, email, password, phone, role, status) VALUES (?, ?, ?, ?, 'admin', 'active')",
        [$name, $email, password_hash($password, PASSWORD_DEFAULT), $phone]
    );

    unset($_SESSION['create_admin_token']);
    flash('Administrator account created successfully. The new administrator can now sign in.');
    redirect('admin/users/index.php?role=admin');
}

require INC . '/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">PEOPLE / ADMINISTRATORS</p>
        <h1>Add Administrator</h1>
        <p>Create an administrator account for a trusted team member.</p>
    </div>
    <a class="btn btn-light" href="index.php?role=admin"><i class="bi bi-arrow-left me-1"></i>Back to Administrators</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <form method="post" action="create_admin.php">
                    <input type="hidden" name="token" value="<?= e($_SESSION['create_admin_token']) ?>">

                    <div class="mb-3">
                        <label class="form-label" for="name">Full name</label>
                        <input class="form-control" type="text" id="name" name="name" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <input class="form-control" type="email" id="email" name="email" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="phone">Contact number <span class="text-muted">(optional)</span></label>
                        <input class="form-control" type="text" id="phone" name="phone" maxlength="20" placeholder="09XX-XXX-XXXX">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Temporary password</label>
                            <input class="form-control" type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
                            <div class="form-text">At least 8 characters.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="confirm">Confirm password</label>
                            <input class="form-control" type="password" id="confirm" name="confirm" minlength="8" autocomplete="new-password" required>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Create Administrator</button>
                        <a class="btn btn-light" href="index.php?role=admin">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 bg-light">
            <div class="card-body p-4">
                <h2 class="h5"><i class="bi bi-shield-lock me-2"></i>Administrator access</h2>
                <p class="text-muted mb-3">Administrator accounts have access to user records, bookings, services, payments, reports, and reviews.</p>
                <ul class="small text-muted ps-3 mb-0">
                    <li>Only a signed-in administrator can open this page.</li>
                    <li>The new account is created with the Administrator role automatically.</li>
                    <li>Public registration cannot create administrator accounts.</li>
                    <li>Share the temporary password privately and ask the new administrator to change it after signing in.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php require INC . '/footer.php'; ?>
