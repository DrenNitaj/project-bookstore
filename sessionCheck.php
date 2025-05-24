<?php
// Ensure this is the only place where session_start() is called
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session if not started already
}

// Define timeout duration (e.g., 30 minutes)
$timeout_duration = 1800;

// Check if the user or admin is logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {

    // Check if the timeout session variable exists
    if (isset($_SESSION['last_activity'])) {
        // Calculate the session's lifetime
        $session_lifetime = time() - $_SESSION['last_activity'];

        // If the session lifetime exceeds the timeout duration, log out the user/admin
        if ($session_lifetime > $timeout_duration) {
            // Store whether it's an admin or a regular user before destroying the session
            $is_admin = isset($_SESSION['admin_id']);

            // Unset all session variables
            session_unset();

            // Destroy the session
            session_destroy();

            // Redirect based on whether it was an admin or user session
            if ($is_admin) {
                // Admin session expired
                echo "<script>
                        alert('Your session has expired. Please sign in again.');
                        window.location.href = 'signinAdmin.php';
                      </script>";
            } else {
                // User session expired
                echo "<script>
                        alert('Your session has expired. Please sign in again.');
                        window.location.href = 'signin.php';
                      </script>";
            }
            exit;
        }
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();

} else {
    // // Redirect to the sign-in page if no session exists
    // // To avoid redirection loop, add a query parameter or use a session variable to check
    // if (basename($_SERVER['PHP_SELF']) !== 'signin.php') {
    //     header("Location: signin.php"); // Redirect to user sign-in page
    //     exit;
    // }
}
?>
