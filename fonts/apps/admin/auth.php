<?php
 session_start();
 if(!isset($_SESSION['SESS_ADMIN_ID']) || (trim($_SESSION['SESS_ADMIN_ID']) == '') || $_SESSION['unit'] != "admin") {
 	header("location:../login.php");
 	exit();
 }

 
?>
