<?php
	include "dbconn.php";
	if(isset($_POST['register'])){
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$username = $_POST['username'];
		$password = $_POST['password'];

		$insert = "INSERT INTO accounts(username,password,firstname,lastname,role) VALUES('$username','$password','$fname','$lname','customer')";
		$result = mysqli_query($conn, $insert);

		if($result){
			header("location:login.php");
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register</title>
	<style>
		table{margin:auto;}
		table tr td{padding:5px;}
		table tr td input{padding:5px;}
	</style>
</head>
<body>
	<form method="post">
		<table>
			<tr><td><h2>OneHome Registration</h2></td></tr>
			<tr><td><input type="text" name="fname" placeholder="First Name" required></td></tr>
			<tr><td><input type="text" name="lname" placeholder="Last Name" required></td></tr>
			<tr><td><input type="text" name="username" placeholder="Username" required></td></tr>
			<tr><td><input type="password" name="password" placeholder="Password" required></td></tr>
			<tr><td><a href="index.php">Cancel</a> | <button name="register">Register</button></td></tr>
		</table>
	</form>
</body>
</html>
