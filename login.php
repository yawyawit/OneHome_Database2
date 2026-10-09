<?php
	session_start();
	include "dbconn.php";

	if(isset($_POST['login_btn'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		$fetch = "SELECT * FROM accounts WHERE username = '$username' ";
		$result = mysqli_query($conn, $fetch);

		if(mysqli_num_rows($result)<1){
			echo "<script>alert('No accounts'); </script>";
		}
		else{
			while ($row = mysqli_fetch_assoc($result)) {
				if($password == $row['password'] && $row['role'] == "admin"){
					$_SESSION["USERNAME"] = $row['username'];
					$_SESSION["FNAME"] = $row['firstname'];
					$_SESSION["LNAME"] = $row['lastname'];
					header("location:admin/index.php");
				}
				else if($password == $row['password'] && $row['role'] == "customer"){
					$_SESSION["USERNAME"] = $row['username'];
					$_SESSION["FNAME"] = $row['firstname'];
					$_SESSION["LNAME"] = $row['lastname'];
					header("location:customer/index.php");
				}
				else{
					header("location:index.php");
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<style>
		table{margin:auto;}
		table tr td{padding:5px;}
		table tr td input{padding:5px;}
	</style>
</head>
<body>
	<form method="post">
		<table>
			<tr><td><h2>OneHome Login</h2></td></tr>
			<tr><td><input type="text" name="username" placeholder="Username" required></td></tr>
			<tr><td><input type="password" name="password" placeholder="Password" required></td></tr>
			<tr><td><a href="index.php">Back</a> | <button name="login_btn">Login</button></td></tr>
		</table>
	</form>
</body>
</html>
