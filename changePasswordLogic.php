<?php
// Start the session
session_start();

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

if(empty($_SESSION['user_id'])){
    header("Location: signin.php");
}

$user_id = $_SESSION['user_id'];
$new_password = $_POST['new_password'];
$confirmed_password = $_POST['confirmed_password'];

if ($new_password !== $confirmed_password) {
  echo "New passwords do not match.";
  exit;
}

$password = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
$sql_update_password = $conn->prepare($sql);
$sql_update_password->bindParam(':password', $password);
$sql_update_password->bindParam(':user_id', $user_id);
$sql_update_password->execute();


$redirectUrl = "account.php?action=password_changed";

header("Location: " . $redirectUrl);
exit();

?>