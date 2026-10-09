<?php
	include '../dbconn.php';
	$id = $_GET['id'];

	$fetch = "SELECT * FROM services WHERE id = '$id'";
	$result = mysqli_query($conn, $fetch);
	$row = mysqli_fetch_assoc($result);

	if(isset($_POST['update'])){
		$name = $_POST['service_name'];
		$amount = $_POST['amount'];
		$availability = $_POST['availability'];

		$update = "UPDATE services SET service_name='$name', amount='$amount', availability='$availability' WHERE id='$id'";
		$result2 = mysqli_query($conn, $update);
		if($result2){
			header("location:services.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Update Service</title></head>
<body>
	<h2>Update Service</h2>
	<form method="post">
		<table>
			<tr><td>Service</td><td><input type="text" name="service_name" value="<?php echo $row['service_name']; ?>" required></td></tr>
			<tr><td>Amount</td><td><input type="number" name="amount" value="<?php echo $row['amount']; ?>" required></td></tr>
			<tr><td>Availability</td><td>
				<select name="availability">
					<option <?php if($row['availability']=='Available') echo 'selected'; ?>>Available</option>
					<option <?php if($row['availability']=='Unavailable') echo 'selected'; ?>>Unavailable</option>
				</select>
			</td></tr>
			<tr><td><a href="services.php">Cancel</a></td><td><button name="update">Update</button></td></tr>
		</table>
	</form>
</body>
</html>
