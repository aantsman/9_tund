<?php
    
	require_once("../config_global.php");
	require_once("./user.class.php");
    $database = "if15_anniant";
	
	session_start();
	
	$mysqli = new mysqli($servername, $serverusername, $serverpassword, $database);
	
	$User = new User($mysqli);
	
?>