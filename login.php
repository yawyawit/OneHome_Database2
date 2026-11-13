<?php
require_once __DIR__ . '/includes/auth.php';
$u = current_user();
if ($u && $u['status'] === 'active')
    redirect(home_for($u['role']));
$title = 'Login';
require INC . '/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-3">Login</h3>
                <form method="post" action="<?= url('actions/process_login.php') ?>">
                    <label class="form-label">Email</label><input type="email" name="email" class="form-control mb-3"
                        required>
                    <label class="form-label">Password</label><input type="password" name="password"
                        class="form-control mb-3" required>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 mb-0">No account yet? <a href="<?= url('register.php') ?>">Register here</a></p>
            </div>
        </div>

    </div>
</div>
<?php require INC . '/footer.php'; ?>