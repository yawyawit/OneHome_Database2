<?php
function e($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
function url($p)
{
    return BASE_URL . '/' . ltrim($p, '/');
}
function redirect($p)
{
    header('Location: ' . url($p));
    exit;
}
function flash($msg, $type = 'success')
{
    $_SESSION['flash'] = [$msg, $type];
}
function post_only()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die('Invalid request.');
    }
}
function money($n)
{
    return '₱' . number_format((float) $n, 2);
}
function fdate($d)
{
    return date('M d, Y', strtotime($d));
}
function ftime($t)
{
    return date('g:i A', strtotime($t));
}
function stars($n)
{
    $n = (int) round($n);
    return '<span class="star">' . str_repeat('★', $n) . str_repeat('☆', 5 - $n) . '</span>';
}
function rating_text($avg, $count)
{
    return $count ? stars($avg) . ' ' . $avg . ' (' . $count . ')' : '<span class="text-muted">No ratings yet</span>';
}
function badge($s)
{
    $c = [
        'Pending' => 'warning text-dark',
        'Confirmed' => 'info text-dark',
        'Ongoing' => 'primary',
        'Completed' => 'success',
        'Cancelled' => 'secondary',
        'Declined' => 'danger',
        'Paid' => 'success',
        'Unpaid' => 'warning text-dark',
        'Refunded' => 'dark',
        'active' => 'success',
        'pending' => 'warning text-dark',
        'inactive' => 'danger'
    ];
    return '<span class="badge bg-' . ($c[$s] ?? 'secondary') . '">' . e(ucfirst((string) $s)) . '</span>';
}
function pay_label($m)
{
    return $m === 'card' ? 'Card (simulated)' : 'Pay after service (cash)';
}

// ---------- Database helpers (prepared statements) ----------
function db_run($sql, $params = [])
{
    global $conn;
    $st = mysqli_prepare($conn, $sql);
    if (!$st)
        die('SQL error: ' . mysqli_error($conn));
    if ($params)
        mysqli_stmt_bind_param($st, str_repeat('s', count($params)), ...$params);
    if (!mysqli_stmt_execute($st))
        die('SQL error: ' . mysqli_stmt_error($st));
    return $st;
}
function db_all($sql, $params = [])
{
    $r = mysqli_stmt_get_result(db_run($sql, $params));
    return $r ? mysqli_fetch_all($r, MYSQLI_ASSOC) : [];
}
function db_one($sql, $params = [])
{
    $a = db_all($sql, $params);
    return $a ? $a[0] : null;
}
function db_val($sql, $params = [])
{
    $r = db_one($sql, $params);
    return $r ? array_values($r)[0] : 0;
}
function db_exec($sql, $params = [])
{
    global $conn;
    db_run($sql, $params);
    return mysqli_insert_id($conn);
}

// ---------- Uploads & avatars ----------
function save_upload($field, $folder)
{
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== 0)
        return '';
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) || $_FILES[$field]['size'] > 2 * 1024 * 1024 || !@getimagesize($_FILES[$field]['tmp_name']))
        return '';
    $name = uniqid('img_') . '.' . $ext;
    move_uploaded_file($_FILES[$field]['tmp_name'], __DIR__ . '/../' . $folder . '/' . $name);
    return $name;
}
function avatar($name, $file = '', $size = 60)
{
    if ($file && file_exists(__DIR__ . '/../images/profiles/' . $file))
        return '<img src="' . url('images/profiles/' . $file) . '" class="avatar" style="width:' . $size . 'px;height:' . $size . 'px">';
    return '<span class="avatar-initial" style="width:' . $size . 'px;height:' . $size . 'px;font-size:' . ($size / 2.5) . 'px">' . e(strtoupper(substr($name, 0, 1))) . '</span>';
}

// ---------- Schedule rules ----------
function works_on($emp, $date)
{
    return in_array(date('D', strtotime($date)), explode(',', $emp['available_days']));
}
// An employee is NOT free if they already have a Confirmed/Ongoing job within 2 hours of this time
function employee_free($eid, $date, $time, $ignore = 0)
{
    return db_val("SELECT COUNT(*) FROM bookings WHERE employee_id=? AND booking_date=? AND status IN ('Confirmed','Ongoing') AND id<>? AND ABS(TIME_TO_SEC(booking_time)-TIME_TO_SEC(?))<7200", [$eid, $date, $ignore, $time]) == 0;
}

// ---------- Bookings ----------
function list_bookings($where = '1', $params = [], $order = 'b.booking_date DESC, b.booking_time DESC')
{
    return db_all("SELECT b.*, s.name sname, c.name cname, c.phone cphone, c.email cemail, e.name ename, e.phone ephone,
        p.id payid, p.status pstatus, p.method pmethod, p.card_last4, p.paid_at,
        (SELECT COUNT(*) FROM reviews r WHERE r.booking_id=b.id) reviewed
        FROM bookings b JOIN services s ON s.id=b.service_id JOIN users c ON c.id=b.customer_id JOIN users e ON e.id=b.employee_id
        LEFT JOIN payments p ON p.booking_id=b.id WHERE $where ORDER BY $order", $params);
}
function get_booking($id)
{
    $r = list_bookings('b.id=?', [$id]);
    return $r ? $r[0] : null;
}

function booking_info($b)
{ ?>
    <table class="table table-bordered bg-white">
        <tr>
            <th width="30%">Booking #</th>
            <td><?= $b['id'] ?></td>
        </tr>
        <tr>
            <th>Service</th>
            <td><?= e($b['sname']) ?></td>
        </tr>
        <tr>
            <th>Customer</th>
            <td><?= e($b['cname']) ?> &middot; <?= e($b['cphone']) ?> &middot; <?= e($b['cemail']) ?></td>
        </tr>
        <tr>
            <th>Employee</th>
            <td><?= e($b['ename']) ?> &middot; <?= e($b['ephone']) ?></td>
        </tr>
        <tr>
            <th>Schedule</th>
            <td><?= fdate($b['booking_date']) ?> at <?= ftime($b['booking_time']) ?></td>
        </tr>
        <tr>
            <th>Service location</th>
            <td><?= e($b['address']) ?></td>
        </tr>
        <tr>
            <th>Work needed</th>
            <td><?= nl2br(e($b['description'])) ?></td>
        </tr>
        <?php if ($b['photo']): ?>
            <tr>
                <th>Photo</th>
                <td><img src="<?= url('uploads/' . $b['photo']) ?>" class="img-thumbnail" style="max-width:250px"></td>
            </tr><?php endif; ?>
        <tr>
            <th>Estimated price</th>
            <td><?= money($b['price']) ?></td>
        </tr>
        <tr>
            <th>Payment</th>
            <td><?= pay_label($b['pmethod']) ?> &mdash; <?= badge($b['pstatus']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= badge($b['status']) ?><?php if ($b['decline_reason']): ?> &mdash; Reason:
                    <?= e($b['decline_reason']) ?>    <?php endif; ?></td>
        </tr>
    </table>
<?php }

function bookings_table($rows, $role)
{
    if (!$rows) {
        echo '<div class="alert alert-secondary">No bookings found.</div>';
        return;
    }
    $link = ['customer' => 'customer/booking_details.php?id=', 'employee' => 'employee/booking_details.php?id=', 'admin' => 'admin/bookings/view.php?id='][$role]; ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th><?= $role === 'customer' ? 'Employee' : 'Customer' ?></th><?php if ($role === 'admin'): ?>
                        <th>Employee</th><?php endif; ?>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><?= e($b['sname']) ?></td>
                        <td><?= e($role === 'customer' ? $b['ename'] : $b['cname']) ?></td>
                        <?php if ($role === 'admin'): ?>
                            <td><?= e($b['ename']) ?></td><?php endif; ?>
                        <td><?= fdate($b['booking_date']) ?><br><small><?= ftime($b['booking_time']) ?></small></td>
                        <td><?= badge($b['status']) ?></td>
                        <td><?= badge($b['pstatus']) ?></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="<?= url($link . $b['id']) ?>">View</a>
                            <?php if ($role === 'employee')
                                echo job_buttons($b); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php }

// Buttons the employee can press depending on the booking status
function job_buttons($b)
{
    $id = $b['id'];
    $f = function ($file, $label, $class, $extra = '', $action = '') use ($id) {
        return '<form method="post" action="' . url('employee/' . $file) . '" class="d-inline"><input type="hidden" name="id" value="' . $id . '">'
            . ($action ? '<input type="hidden" name="action" value="' . $action . '">' : '') . $extra
            . '<button class="btn btn-sm btn-' . $class . '">' . $label . '</button></form> ';
    };
    $s = $b['status'];
    $o = '';
    if ($s === 'Pending') {
        $o .= $f('accept_booking.php', 'Accept', 'success');
        $o .= $f('decline_booking.php', 'Decline', 'danger', '<input name="reason" class="form-control form-control-sm d-inline-block w-auto" placeholder="Reason" required> ');
    } elseif ($s === 'Confirmed')
        $o .= $f('update_job_status.php', 'Start Job', 'primary', '', 'start');
    elseif ($s === 'Ongoing')
        $o .= $f('update_job_status.php', 'Mark Completed', 'success', '', 'complete');
    elseif ($s === 'Completed' && $b['pstatus'] === 'Unpaid' && $b['pmethod'] === 'cash')
        $o .= $f('update_job_status.php', 'Cash Received', 'warning', '', 'paid');
    return $o;
}

// ---------- Reusable UI pieces ----------
function stat_card($label, $value, $icon = 'bi-bar-chart', $color = 'primary')
{
    return '<div class="col-6 col-md-4 col-lg-3 mb-3"><div class="card border-' . $color . ' h-100"><div class="card-body"><i class="bi ' . $icon . ' text-' . $color . '"></i> <span class="text-muted small">' . e($label) . '</span><div class="fs-3 fw-bold">' . $value . '</div></div></div></div>';
}
function service_card($s, $book = true)
{
    ob_start(); ?>
    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100 text-center shadow-sm">
            <div class="card-body">
                <i class="bi <?= e($s['icon']) ?> service-icon"></i>
                <h5 class="mt-2"><?= e($s['name']) ?></h5>
                <p class="small text-muted"><?= e($s['description']) ?></p>
                <p class="fw-bold mb-0">Starts at <?= money($s['price']) ?></p>
            </div>
            <?php if ($book): ?>
                <div class="card-footer bg-white"><a class="btn btn-sm btn-outline-secondary"
                        href="<?= url('customer/service_details.php?id=' . $s['id']) ?>">Details</a>
                    <a class="btn btn-sm btn-primary"
                        href="<?= url('customer/select_employee.php?service_id=' . $s['id']) ?>">Book Now</a>
                </div><?php endif; ?>
        </div>
    </div>
    <?php return ob_get_clean();
}

function service_form($s = null)
{
    $icons = ['bi-droplet-fill' => 'Droplet (Plumbing)', 'bi-lightning-charge-fill' => 'Lightning (Electrical)', 'bi-stars' => 'Stars (Cleaning)', 'bi-wrench-adjustable' => 'Wrench (Repair)', 'bi-bug-fill' => 'Bug (Pest Control)', 'bi-paint-bucket' => 'Paint bucket', 'bi-flower1' => 'Flower (Gardening)', 'bi-tools' => 'Tools (Other)', 'bi-house-gear-fill' => 'House (Other)']; ?>
    <form method="post" class="card card-body">
        <label class="form-label">Service Name</label><input class="form-control mb-2" name="name"
            value="<?= e($s['name'] ?? '') ?>" required>
        <label class="form-label">Description</label><textarea class="form-control mb-2" name="description" rows="3"
            required><?= e($s['description'] ?? '') ?></textarea>
        <label class="form-label">Starting Price (₱)</label><input type="number" min="0" step="0.01"
            class="form-control mb-2" name="price" value="<?= e($s['price'] ?? '') ?>" required>
        <label class="form-label">Icon</label><select name="icon"
            class="form-select mb-3"><?php foreach ($icons as $k => $v): ?>
                <option value="<?= $k ?>" <?= ($s['icon'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?>
        </select>
        <?php if ($s): ?>
            <div class="form-check mb-3"><input type="checkbox" class="form-check-input" name="is_active" id="ia"
                    <?= $s['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="ia">Active (shown to
                    customers)</label></div><?php endif; ?>
        <div><button class="btn btn-primary">Save</button> <a class="btn btn-secondary"
                href="<?= url('admin/services/index.php') ?>">Cancel</a></div>
    </form>
<?php }

// Name/email/phone/address form + change password form (used by all 3 roles)
function account_forms($u)
{ ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Account Information</div>
                <div class="card-body">
                    <form method="post" action="<?= url('actions/process_profile.php') ?>"><input type="hidden"
                            name="action" value="info">
                        <label class="form-label">Full Name</label><input class="form-control mb-2" name="name"
                            value="<?= e($u['name']) ?>" required>
                        <label class="form-label">Email</label><input type="email" class="form-control mb-2" name="email"
                            value="<?= e($u['email']) ?>" required>
                        <label class="form-label">Contact Number</label><input class="form-control mb-2" name="phone"
                            value="<?= e($u['phone']) ?>" required>
                        <label class="form-label">Home Address</label><textarea class="form-control mb-2" name="address"
                            rows="2"><?= e($u['address']) ?></textarea>
                        <label class="form-label">Current Password <small class="text-muted">(needed only if you change your
                                email or contact number)</small></label>
                        <input type="password" class="form-control mb-3" name="current_password">
                        <button class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <form method="post" action="<?= url('actions/process_profile.php') ?>"><input type="hidden"
                            name="action" value="password">
                        <label class="form-label">Current Password</label><input type="password" class="form-control mb-2"
                            name="current_password" required>
                        <label class="form-label">New Password (min. 6 characters)</label><input type="password"
                            class="form-control mb-2" name="new_password" minlength="6" required>
                        <label class="form-label">Confirm New Password</label><input type="password"
                            class="form-control mb-3" name="confirm_password" required>
                        <button class="btn btn-warning">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
