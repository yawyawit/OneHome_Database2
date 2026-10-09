<?php
	session_start();
	include '../dbconn.php';

	if(!isset($_SESSION["USERNAME"])){
		header("location:../index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Requests</title>
</head>
<body>
	<h2>My Service Requests</h2>
	<a href="index.php">Back to Request Form</a> | <a href="logout.php">Logout</a>
	<br><br>
	<table border="1">
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Address</th>
			<th>Service</th>
			<th>Amount</th>
			<th>Status</th>
		</tr>
		<?php
			$fetch = "SELECT service_requests.*, services.service_name FROM service_requests INNER JOIN services ON service_requests.service_id = services.id WHERE service_requests.customer_username = '" . $_SESSION['USERNAME'] . "'";
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
				</tr>
		<?php
			}
		?>
	</table>
</body>
</html>
