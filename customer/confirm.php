<?php
	session_start();
	include '../dbconn.php';

	if(!isset($_SESSION["USERNAME"]) || !isset($_SESSION['REQUEST_SERVICE'])){
		header("location:index.php");
	}

	$service_id = $_SESSION['REQUEST_SERVICE'];
	$fetch = "SELECT * FROM services WHERE id = '$service_id'";
	$result = mysqli_query($conn, $fetch);
	$service = mysqli_fetch_assoc($result);

	if(isset($_POST['confirm'])){
		$fname = $_SESSION['REQUEST_FNAME'];
		$lname = $_SESSION['REQUEST_LNAME'];
		$address = $_SESSION['REQUEST_ADDRESS'];
		$amount = $_SESSION['REQUEST_AMOUNT'];

		$insert = "INSERT INTO service_requests(customer_username,firstname,lastname,address,service_id,amount,status) VALUES('" . $_SESSION['USERNAME'] . "','$fname','$lname','$address','$service_id','$amount','Pending')";
		$result2 = mysqli_query($conn, $insert);

		unset($_SESSION['REQUEST_FNAME']);
		unset($_SESSION['REQUEST_LNAME']);
		unset($_SESSION['REQUEST_ADDRESS']);
		unset($_SESSION['REQUEST_SERVICE']);
		unset($_SESSION['REQUEST_AMOUNT']);

		if($result2){
			header("location:requests.php");
		}
	}

	if(isset($_POST['cancel'])){
		unset($_SESSION['REQUEST_FNAME']);
		unset($_SESSION['REQUEST_LNAME']);
		unset($_SESSION['REQUEST_ADDRESS']);
		unset($_SESSION['REQUEST_SERVICE']);
		unset($_SESSION['REQUEST_AMOUNT']);
		header("location:index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Confirm Request</title>
</head>
<body>
	<table border="1" style="width:650px; margin:auto;">
		<tr><td><h2>Confirm Your Request</h2></td></tr>
		<tr><td>
			<table>
				<tr><td>First Name</td><td><?php echo $_SESSION['REQUEST_FNAME']; ?></td></tr>
				<tr><td>Last Name</td><td><?php echo $_SESSION['REQUEST_LNAME']; ?></td></tr>
				<tr><td>Address</td><td><?php echo $_SESSION['REQUEST_ADDRESS']; ?></td></tr>
				<tr><td>Service</td><td><?php echo $service['service_name']; ?></td></tr>
				<tr><td>Amount</td><td><?php echo $_SESSION['REQUEST_AMOUNT']; ?></td></tr>
				<tr><td>Status</td><td>Pending</td></tr>
			</table>
			<br>
			<form method="post">
				<button name="confirm">Confirm</button>
				<button name="cancel">Cancel</button>
			</form>
		</td></tr>
	</table>
</body>
</html>
