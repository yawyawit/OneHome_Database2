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
	<title>Manage Services</title>
</head>
<body>
	<h2>Manage Services</h2>
	<a href="index.php">Dashboard</a> | <a href="add_service.php">Add Service</a>
	<br><br>
	<table border="1">
		<tr><th>Service</th><th>Amount</th><th>Availability</th><th>Actions</th></tr>
		<?php
			$fetch = "SELECT * FROM services";
			$result = mysqli_query($conn, $fetch);
			while($row = mysqli_fetch_assoc($result)){
		?>
		<tr>
			<td><?php echo $row['service_name']; ?></td>
			<td><?php echo $row['amount']; ?></td>
			<td><?php echo $row['availability']; ?></td>
			<td>
				<a href="edit_service.php?id=<?php echo $row['id']; ?>">Update</a>
				<a href="delete_service.php?id=<?php echo $row['id']; ?>">Delete</a>
			</td>
		</tr>
		<?php } ?>
	</table>
</body>
</html>
