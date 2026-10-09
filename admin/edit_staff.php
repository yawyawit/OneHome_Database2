<?php
	include '../dbconn.php';
	$id = $_GET['id'];

	$fetch = "SELECT * FROM staff WHERE id = '$id'";
	$result = mysqli_query($conn, $fetch);
	$row = mysqli_fetch_assoc($result);

	if(isset($_POST['update'])){
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$specialty = $_POST['specialty'];

		$update = "UPDATE staff SET firstname='$fname', lastname='$lname', specialty='$specialty' WHERE id='$id'";
		$result2 = mysqli_query($conn, $update);
		if($result2){
			header("location:staff.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Update Staff</title></head>
<body>
	<h2>Update Staff</h2>
	<form method="post">
		<table>
			<tr><td>First Name</td><td><input type="text" name="fname" value="<?php echo $row['firstname']; ?>" required></td></tr>
			<tr><td>Last Name</td><td><input type="text" name="lname" value="<?php echo $row['lastname']; ?>" required></td></tr>
			<tr><td>Specialty</td><td><input type="text" name="specialty" value="<?php echo $row['specialty']; ?>" required></td></tr>
			<tr><td><a href="staff.php">Cancel</a></td><td><button name="update">Update</button></td></tr>
		</table>
	</form>
</body>
</html>
