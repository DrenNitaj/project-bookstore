<?php
// connection iwth database
include_once("config.php");

include('sessionCheck.php');

// session_start();

// Check if a user is already signed in
if (isset($_SESSION['user_id'])) {
    // User is signed in, ask if they want to log out
    echo "<script>
            if (confirm('You are already signed in as User. Do you want to log out and switch accounts?')) {
                window.location.href = 'logout.php'; // Redirect to logout
            } else {
                window.location.href = 'signinBooks.php'; // Continue with the current session
            }
          </script>";
    exit();
} elseif (isset($_SESSION['admin_id'])) {
    // Admin is signed in, ask if they want to log out
    echo "<script>
            if (confirm('You are already signed in as Staff Member. Do you want to log out and switch accounts?')) {
                window.location.href = 'logoutAdmin.php'; // Redirect to admin logout
            } else {
                window.location.href = 'addBooks.php'; // Redirect to admin dashboard or appropriate admin page
            }
          </script>";
    exit();
}


if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE username=:username";
    $selectUser = $conn->prepare($sql);
    $selectUser->bindParam(':username', $username);
    $selectUser->execute();

    if ($selectUser->rowCount() > 0){
        $userData = $selectUser->fetch();
        if (password_verify($password, $userData['password'])){
            $_SESSION['user_name'] = $userData['name'];
            $_SESSION['user_id'] = $userData['user_id'];

            // Set session variable to show alert
            $_SESSION['show_welcome_alert'] = true;

            // Save the current date and time to the last_login column
            $currentDateTime = date('Y-m-d H:i:s'); // Format for MySQL DATETIME
            $updateSql = "UPDATE users SET last_login = :last_login WHERE user_id = :user_id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':last_login', $currentDateTime);
            $updateStmt->bindParam(':user_id', $userData['user_id']);
            $updateStmt->execute();

            header("Location: signinBooks.php");

        } else {
            echo "Password is incorrect";
        }
    } else {
        echo "User not found";
    }
}

?>