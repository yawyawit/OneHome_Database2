<?php
	include '../dbconn.php';
	$id = $_GET['id'];
	$delete = "DELETE FROM staff WHERE id = '$id'";
	mysqli_query($conn, $delete);
	header("location:staff.php");
?>
