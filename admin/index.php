<?php
	session_start();
	include '../dbconn.php';
	if(!isset($_SESSION["USERNAME"])){
		session_destroy();
		header("location:../index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>OneHome Admin</title>
</head>
<body>
	<h1>OneHome Company Dashboard</h1>
	<p>Welcome <?php echo $_SESSION['USERNAME']; ?></p>
	<a href="services.php">Manage Services</a> |
	<a href="staff.php">Manage Staff</a> |
	<a href="logout.php">Logout</a>
	<br><br>
	<table border="1">
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Address</th>
			<th>Service</th>
			<th>Amount</th>
			<th>Status</th>
			<th>Assigned Staff</th>
			<th>Action</th>
		</tr>
		<?php
			$fetch = "SELECT service_requests.*, services.service_name, staff.firstname AS staff_fname, staff.lastname AS staff_lname FROM service_requests INNER JOIN services ON service_requests.service_id = services.id LEFT JOIN staff ON service_requests.staff_id = staff.id ORDER BY service_requests.id DESC";
			$result = mysqli_query($conn, $fetch);
			while($row = mysqli_fetch_assoc($result)){
		?>
				<tr>
					<td><?php echo $row['firstname']; ?></td>
					<td><?php echo $row['lastname']; ?></td>
					<td><?php echo $row['address']; ?></td>
					<td><?php echo $row['service_name']; ?></td>
					<td><?php echo $row['amount']; ?></td>
					<td><?php echo $row['status']; ?></td>
					<td><?php echo $row['staff_fname'] . ' ' . $row['staff_lname']; ?></td>
					<td>
						<a href="update_request.php?id=<?php echo $row['id']; ?>">Update</a>
						<a href="delete_request.php?id=<?php echo $row['id']; ?>">Delete</a>
					</td>
				</tr>
		<?php
			}
		?>
	</table>
</body>
</html>
