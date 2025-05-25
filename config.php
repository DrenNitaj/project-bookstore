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



	$cohereApiKey = getenv('COHERE_API_KEY'); // Load from environment variable
	if (!$cohereApiKey) {
		die("API key not set in environment variable COHERE_API_KEY");
	}

	define('COHERE_API_KEY', $cohereApiKey);




?>