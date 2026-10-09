<?php
	session_start();
	include '../dbconn.php';
	if(!isset($_SESSION["USERNAME"])){
		header("location:../index.php");
	}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Manage Staff</title></head>
<body>
	<h2>Manage Staff</h2>
	<a href="index.php">Dashboard</a> | <a href="add_staff.php">Add Staff</a>
	<br><br>
	<table border="1">
		<tr><th>First Name</th><th>Last Name</th><th>Specialty</th><th>Actions</th></tr>
		<?php
			$fetch = "SELECT * FROM staff";
			$result = mysqli_query($conn, $fetch);
			while($row = mysqli_fetch_assoc($result)){
		?>
		<tr>
			<td><?php echo $row['firstname']; ?></td>
			<td><?php echo $row['lastname']; ?></td>
			<td><?php echo $row['specialty']; ?></td>
			<td>
				<a href="edit_staff.php?id=<?php echo $row['id']; ?>">Update</a>
				<a href="delete_staff.php?id=<?php echo $row['id']; ?>">Delete</a>
			</td>
		</tr>
		<?php } ?>
	</table>
</body>
</html>
