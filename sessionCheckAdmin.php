<?php
// Ensure this is the only place where session_start() is called
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session if not started already
}

if (isset($_SESSION['admin_id'])){

    // Define timeout duration (e.g., 30 minutes)
    $timeout_duration = 1800;

    // Check if the timeout session variable exists
    if (isset($_SESSION['last_activity'])) {
        // Calculate the session's lifetime
        $session_lifetime = time() - $_SESSION['last_activity'];

        // If the session lifetime exceeds the timeout duration, log out the user
        if ($session_lifetime > $timeout_duration) {
            // Unset all of the session variables
            session_unset();

            // Destroy the session
            session_destroy();

            // Alert the user and redirect to login page with a timeout message
            echo "<script>
                    alert('Your session has expired. Please sign in again.');
                    window.location.href = 'signinAdmin.php';
                </script>";
            exit;
        }
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();

} else{
    // header("Location: addBooks.php");
}
?>
