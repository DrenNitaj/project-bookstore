<?php
    // connect with database
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


        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $tempPassword = $_POST['password'];
        $password = password_hash($tempPassword, PASSWORD_DEFAULT);
        $confirmed_password = $_POST['confirmed_password'];
        $phonenumber = $_POST['phonenumber'];
        $address = $_POST['address'];

        // Check if passwords match
        if ($tempPassword !== $confirmed_password) {
            echo "Passwords do not match.";
            exit; // Stop execution if passwords do not match
        }

        $password = password_hash($tempPassword, PASSWORD_DEFAULT);

        $sql = "SELECT email FROM users WHERE email=:email";
        $sqlCheckEmails = $conn->prepare($sql);
        $sqlCheckEmails->bindParam(':email', $email);
        $sqlCheckEmails->execute();

        $sql = "SELECT username FROM users WHERE username=:username";
        $sqlCheckUsernames = $conn->prepare($sql);
        $sqlCheckUsernames->bindParam(':username', $username);
        $sqlCheckUsernames->execute();

        if ($sqlCheckUsernames->rowCount() > 0 & $sqlCheckEmails->rowCount() > 0){
            echo "Username and email already exist. Try some others.";
        } else if ($sqlCheckUsernames->rowCount() > 0) {
            echo "Username already exists. Try another one.";
        } else if ($sqlCheckEmails->rowCount() > 0){
            echo "Email already exists. Try another one.";
        } else {

            // Get the current date and time
            $currentDateTime = date('Y-m-d H:i:s'); // Format for MySQL DATETIME



        $sql = "INSERT INTO users(name, surname, username, email, password, phone_number, address, last_login) VALUES (:name, :surname, :username, :email, :password, :phonenumber, :address, :last_login)";

        $sqlPrep = $conn->prepare($sql);

        $sqlPrep->bindParam(':name', $name);
        $sqlPrep->bindParam(':surname', $surname);
        $sqlPrep->bindParam(':username', $username);
        $sqlPrep->bindParam(':email', $email);
        $sqlPrep->bindParam(':password', $password);
        $sqlPrep->bindParam(':phonenumber', $phonenumber);
        $sqlPrep->bindParam(':address', $address);
        $sqlPrep->bindParam(':last_login', $currentDateTime);
        

        $sqlPrep->execute();

        $_SESSION['user_name'] = $name;
        $_SESSION['user_id'] = $conn->lastInsertId();

            
            
        // Set session variable to show alert
        $_SESSION['show_welcome_alert'] = true;


        header("Location: signinBooks.php");

        }