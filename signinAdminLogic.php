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
    // $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username)){
        // echo "Please provide your email address or username";
        echo "Please provide your username";
    } else{
        $sql = "SELECT * FROM admins WHERE username=:username";
        $selectAdmin = $conn->prepare($sql);
        $selectAdmin->bindParam(':username', $username);
        // $selectAdmin->bindParam(':email', $email);
        $selectAdmin->execute();

        if ($selectAdmin->rowCount() > 0){
            $adminData = $selectAdmin->fetch();
            if (password_verify($password, $adminData['password'])){
                $_SESSION['admin_name'] = $adminData['name'];
                $_SESSION['admin_id'] = $adminData['admin_id'];


                // Set session variable to show alert
                $_SESSION['show_welcome_alert'] = true;


                header("Location: addBooks.php");

            } else {
                echo "Password is incorrect";
            }
        } else {
            echo "Admin not found";
        }
    }

}
?>