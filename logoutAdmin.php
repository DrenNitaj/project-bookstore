<?php
session_start(); // Start session if not started already

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login page
header("Location: signinAdmin.php");
exit;
?>