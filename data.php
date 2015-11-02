<?php
	require_once("functions.php");
	
	 
	if(!isset($_SESSION['user_id'])){
		header("Location: login.php");
	}

	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php"); 
	}
  
?>

Tere, <?=$_SESSION['user_email'];?> <a href="?logout=1">Logi välja!</a>




