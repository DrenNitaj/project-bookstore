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



	$cohereApiKey = getenv('COHERE_API_KEY'); // Load from environment (optional for now)




?>