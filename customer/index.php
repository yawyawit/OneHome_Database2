<?php
	session_start();
	include '../dbconn.php';

	if(!isset($_SESSION["USERNAME"])){
		session_destroy();
		header("location:../index.php");
	}

	if(isset($_POST['submit_request'])){
		$_SESSION['REQUEST_FNAME'] = $_POST['fname'];
		$_SESSION['REQUEST_LNAME'] = $_POST['lname'];
		$_SESSION['REQUEST_ADDRESS'] = $_POST['address'];
		$_SESSION['REQUEST_SERVICE'] = $_POST['service'];
		$_SESSION['REQUEST_AMOUNT'] = $_POST['amount'];

		header("location:confirm.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>OneHome Customer</title>
</head>
<body>

	<table border="1" style="width:900px; margin:auto;">
		<tr>
			<td colspan="2">
				<h1>OneHome Household Services</h1>
				Welcome <?php echo $_SESSION['FNAME']; ?> |
				<a href="requests.php">My Requests</a> |
				<a href="logout.php">Logout</a>
			</td>
		</tr>

		<tr>
			<td style="width:250px; vertical-align:top;">
				<h3>Services</h3>

				<?php
					$fetch = "SELECT * FROM services";
					$result = mysqli_query($conn, $fetch);

					while($row = mysqli_fetch_assoc($result)){
						echo $row['service_name'] . " - ";

						if($row['availability'] == 'Available'){
							echo "Available";
						}
						else{
							echo "Unavailable";
						}

						echo "<br>";
					}
				?>
			</td>

			<td>
				<h2>Request a Service</h2>

				<form method="post">
					<table>
						<tr>
							<td>First Name</td>
							<td>
								<input type="text" name="fname" value="<?php echo $_SESSION['FNAME']; ?>" required>
							</td>
						</tr>

						<tr>
							<td>Last Name</td>
							<td>
								<input type="text" name="lname" value="<?php echo $_SESSION['LNAME']; ?>" required>
							</td>
						</tr>

						<tr>
							<td>Address</td>
							<td>
								<input type="text" name="address" required>
							</td>
						</tr>

						<tr>
							<td>Service</td>
							<td>
								<select name="service" id="service" onchange="showAmount()" required>
									<option value="">Select Service</option>

									<?php
										$fetch2 = "SELECT * FROM services";
										$result2 = mysqli_query($conn, $fetch2);

										while($row2 = mysqli_fetch_assoc($result2)){
											if($row2['availability'] == 'Available'){
												echo "<option value='" . $row2['id'] . "' data-amount='" . $row2['amount'] . "'>";
												echo $row2['service_name'] . " - Available";
												echo "</option>";
											}
											else{
												echo "<option value='" . $row2['id'] . "' data-amount='" . $row2['amount'] . "' disabled>";
												echo $row2['service_name'] . " - Unavailable";
												echo "</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>

						<tr>
							<td>Amount</td>
							<td>
								<input type="number" name="amount" id="amount" readonly required>
							</td>
						</tr>

						<tr>
							<td>Status</td>
							<td>Pending</td>
						</tr>

						<tr>
							<td></td>
							<td>
								<button name="submit_request">Submit</button>
							</td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
	</table>

<script>
function showAmount(){
	var service = document.getElementById('service');
	var amount = service.options[service.selectedIndex].getAttribute('data-amount');

	document.getElementById('amount').value = amount ? amount : '';
}
</script>

</body>
</html>