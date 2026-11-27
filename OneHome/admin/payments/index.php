<?php require_once __DIR__ . '/../../includes/auth.php';
require_role('admin');
$title = 'Manage Payments';
$st = in_array($_GET['status'] ?? '', ['Unpaid', 'Paid', 'Refunded']) ? $_GET['status'] : '';
$mt = in_array($_GET['method'] ?? '', ['cash', 'card']) ? $_GET['method'] : '';
$rows = db_all("SELECT p.*, b.id bid, c.name cname, e.name ename, s.name sname, b.status bstatus FROM payments p JOIN bookings b ON b.id=p.booking_id JOIN users c ON c.id=b.customer_id JOIN users e ON e.id=b.employee_id JOIN services s ON s.id=b.service_id
  WHERE (?='' OR p.status=?) AND (?='' OR p.method=?) ORDER BY p.id DESC", [$st, $st, $mt, $mt]);
$received = db_val("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='Paid'");
$unpaid = db_val("SELECT COALESCE(SUM(amount),0) FROM payments p JOIN bookings b ON b.id=p.booking_id WHERE p.status='Unpaid' AND b.status='Completed'");
require INC . '/header.php'; ?>
<h2>Payments</h2>
<div class="row mt-3">
  <?= stat_card('Total received', money($received), 'bi-cash-coin', 'success') ?><?= stat_card('Unpaid (completed jobs)', money($unpaid), 'bi-exclamation-circle', 'warning') ?>
</div>
<form class="row g-2 mb-3" method="get">
  <div class="col-auto"><select name="status" class="form-select">
      <option value="">All status</option><?php foreach (['Unpaid', 'Paid', 'Refunded'] as $s): ?>
        <option <?= $st === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
    </select></div>
  <div class="col-auto"><select name="method" class="form-select">
      <option value="">All methods</option>
      <option value="card" <?= $mt === 'card' ? 'selected' : '' ?>>Card (online)</option>
      <option value="cash" <?= $mt === 'cash' ? 'selected' : '' ?>>Pay after service</option>
    </select></div>
  <div class="col-auto"><button class="btn btn-outline-primary">Filter</button></div>
</form>
<table class="table table-striped bg-white">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Booking</th>
      <th>Customer</th>
      <th>Employee</th>
      <th>Amount</th>
      <th>Method</th>
      <th>Status</th>
      <th>Paid on</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td>#<?= $p['bid'] ?>   <?= e($p['sname']) ?></td>
        <td><?= e($p['cname']) ?></td>
        <td><?= e($p['ename']) ?></td>
        <td><?= money($p['amount']) ?></td>
        <td><?= pay_label($p['method']) ?></td>
        <td><?= badge($p['status']) ?></td>
        <td><?= $p['paid_at'] ? fdate($p['paid_at']) : '-' ?></td>
        <td><a class="btn btn-sm btn-outline-primary" href="view.php?id=<?= $p['id'] ?>">View</a></td>
      </tr><?php endforeach; ?>
  </tbody>
</table>
<?php require INC . '/footer.php'; ?>