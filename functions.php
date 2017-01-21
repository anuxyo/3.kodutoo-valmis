<?php

	require("/home/anusada/config.php");

    session_start();
	
	$database = "if16_anusada_2";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	require("class/Helper.class.php");
	$Helper = new Helper();

?>