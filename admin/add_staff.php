<?php
	include '../dbconn.php';
	if(isset($_POST['add'])){
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$specialty = $_POST['specialty'];

		$insert = "INSERT INTO staff(firstname,lastname,specialty) VALUES('$fname','$lname','$specialty')";
		$result = mysqli_query($conn, $insert);
		if($result){
			header("location:staff.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Staff</title></head>
<body>
	<h2>Add Staff</h2>
	<form method="post">
		<table>
			<tr><td>First Name</td><td><input type="text" name="fname" required></td></tr>
			<tr><td>Last Name</td><td><input type="text" name="lname" required></td></tr>
			<tr><td>Specialty</td><td><input type="text" name="specialty" required></td></tr>
			<tr><td><a href="staff.php">Cancel</a></td><td><button name="add">Add</button></td></tr>
		</table>
	</form>
</body>
</html>
