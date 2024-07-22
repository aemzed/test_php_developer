<?php

    $servername = "localhost";
	$serverusername = "root";
	$serverpassword = "";
	$master_dbname = "test_ilcs";
	
	$connection = new PDO("mysql:host=$servername;dbname=$master_dbname", $serverusername, $serverpassword);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);