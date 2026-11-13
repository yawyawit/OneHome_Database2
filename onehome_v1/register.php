<?php
require_once __DIR__ . '/includes/auth.php';
$u = current_user();
if ($u && $u['status'] === 'active')
	redirect(home_for($u['role']));
$title = 'Register';
$services = db_all("SELECT * FROM services WHERE is_active=1 ORDER BY name");
require INC . '/header.php'; ?>
<div class="row justify-content-center">
	<div class="col-md-7">
		<div class="card shadow-sm">
			<div class="card-body p-4">
				<h3 class="mb-3">Create an Account</h3>
				<form method="post" action="<?= url('actions/process_register.php') ?>">
					<label class="form-label">I am registering as</label>
					<select name="role" id="role" class="form-select mb-3">
						<option value="customer">Customer (Homeowner)</option>
						<option value="employee">Employee / Service Provider</option>
					</select>
					<div class="row">
						<div class="col-md-6 mb-3"><label class="form-label">Full Name</label><input name="name"
								class="form-control" required></div>
						<div class="col-md-6 mb-3"><label class="form-label">Contact Number</label><input name="phone"
								class="form-control" placeholder="09XX-XXX-XXXX" required></div>
					</div>
					<label class="form-label">Email</label><input type="email" name="email" class="form-control mb-3"
						required>
					<label class="form-label">Address (Baguio City)</label><input name="address"
						class="form-control mb-3">
					<div class="row">
						<div class="col-md-6 mb-3"><label class="form-label">Password (min. 6 characters)</label><input
								type="password" name="password" minlength="6" class="form-control" required></div>
						<div class="col-md-6 mb-3"><label class="form-label">Confirm Password</label><input
								type="password" name="confirm" class="form-control" required></div>
					</div>
					<div id="emp-fields" style="display:none">
						<div class="alert alert-info">Employee accounts must be approved by the admin before you can log
							in.</div>
						<label class="form-label">Your skills</label><input name="skills" class="form-control mb-3"
							placeholder="e.g. pipe repair, leak fixing">
						<label class="form-label">Services you can perform</label>
						<div class="mb-3"><?php foreach ($services as $s): ?>
								<div class="form-check"><input class="form-check-input" type="checkbox" name="services[]"
										value="<?= $s['id'] ?>" id="s<?= $s['id'] ?>"><label class="form-check-label"
										for="s<?= $s['id'] ?>"><?= e($s['name']) ?></label></div>
							<?php endforeach; ?>
						</div>
					</div>
					<button class="btn btn-primary w-100">Register</button>
				</form>
				<p class="mt-3 mb-0">Already have an account? <a href="<?= url('login.php') ?>">Login</a></p>
			</div>
		</div>
	</div>
</div>
<?php require INC . '/footer.php'; ?>