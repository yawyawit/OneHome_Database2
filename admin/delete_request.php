<?php
	include '../dbconn.php';
	$id = $_GET['id'];
	$delete = "DELETE FROM service_requests WHERE id = '$id'";
	mysqli_query($conn, $delete);
	header("location:index.php");
?>
