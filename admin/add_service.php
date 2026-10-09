<?php
	include '../dbconn.php';
	if(isset($_POST['add'])){
		$name = $_POST['service_name'];
		$amount = $_POST['amount'];
		$availability = $_POST['availability'];

		$insert = "INSERT INTO services(service_name,amount,availability) VALUES('$name','$amount','$availability')";
		$result = mysqli_query($conn, $insert);
		if($result){
			header("location:services.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Service</title></head>
<body>
	<h2>Add Service</h2>
	<form method="post">
		<table>
			<tr><td>Service</td><td><input type="text" name="service_name" required></td></tr>
			<tr><td>Amount</td><td><input type="number" name="amount" required></td></tr>
			<tr><td>Availability</td><td><select name="availability"><option>Available</option><option>Unavailable</option></select></td></tr>
			<tr><td><a href="services.php">Cancel</a></td><td><button name="add">Add</button></td></tr>
		</table>
	</form>
</body>
</html>
