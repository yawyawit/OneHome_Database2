<?php
	include '../dbconn.php';
	$id = $_GET['id'];

	$fetch = "SELECT * FROM service_requests WHERE id = '$id'";
	$result = mysqli_query($conn, $fetch);
	$row = mysqli_fetch_assoc($result);

	if(isset($_POST['update'])){
		$status = $_POST['status'];
		$staff_id = $_POST['staff_id'];

		$update = "UPDATE service_requests SET status='$status', staff_id='$staff_id' WHERE id='$id'";
		$result2 = mysqli_query($conn, $update);
		if($result2){
			header("location:index.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Update Request</title></head>
<body>
	<h2>Update Customer Request</h2>
	<form method="post">
		<table>
			<tr><td>Customer</td><td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td></tr>
			<tr><td>Address</td><td><?php echo $row['address']; ?></td></tr>
			<tr><td>Amount</td><td><?php echo $row['amount']; ?></td></tr>
			<tr>
				<td>Status</td>
				<td>
					<select name="status">
						<option <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
						<option <?php if($row['status']=='Assigned') echo 'selected'; ?>>Assigned</option>
						<option <?php if($row['status']=='On the Way') echo 'selected'; ?>>On the Way</option>
						<option <?php if($row['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
						<option <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
						<option <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Send Staff</td>
				<td>
					<select name="staff_id">
						<option value="0">Not Assigned</option>
						<?php
							$staff = mysqli_query($conn, "SELECT * FROM staff");
							while($staff_row = mysqli_fetch_assoc($staff)){
								$selected = ($row['staff_id'] == $staff_row['id']) ? 'selected' : '';
								echo "<option value='" . $staff_row['id'] . "' $selected>" . $staff_row['firstname'] . " " . $staff_row['lastname'] . " - " . $staff_row['specialty'] . "</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td><a href="index.php">Cancel</a></td><td><button name="update">Update</button></td></tr>
		</table>
	</form>
</body>
</html>
