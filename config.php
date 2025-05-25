<?php

	$host = "localhost";
	$user = "root";
	$db = "project";
	$pass = "";


	try{
		$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	}catch(Exception $e){
		echo $e ->getMessage();
	}



	define('COHERE_API_KEY', 'BWT719QkDoF9bZ9Dyoo1h5x35ygGUQX63kYxTs71');




?>