<?php
	include '../dbconn.php';
	$id = $_GET['id'];
	$delete = "DELETE FROM services WHERE id = '$id'";
	mysqli_query($conn, $delete);
	header("location:services.php");
?>
