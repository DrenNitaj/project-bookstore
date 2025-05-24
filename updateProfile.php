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

    if (isset($_POST['submit'])){

    
        $id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phonenumber = $_POST['phonenumber'];
        $address = $_POST['address'];



        $sql = "SELECT email FROM users WHERE email=:email AND user_id != :id";
        $sqlCheckEmails = $conn->prepare($sql);
        $sqlCheckEmails->bindParam(':email', $email);
        $sqlCheckEmails->bindParam(':id', $id);
        $sqlCheckEmails->execute();

        $sql = "SELECT username FROM users WHERE username=:username AND user_id != :id";
        $sqlCheckUsernames = $conn->prepare($sql);
        $sqlCheckUsernames->bindParam(':username', $username);
        $sqlCheckUsernames->bindParam(':id', $id);
        $sqlCheckUsernames->execute();

        if ($sqlCheckUsernames->rowCount() > 0 & $sqlCheckEmails->rowCount() > 0){
            echo "Username and email already exist. Try some others.";
        } else if ($sqlCheckUsernames->rowCount() > 0) {
            echo "Username already exists. Try another one.";
        } else if ($sqlCheckEmails->rowCount() > 0){
            echo "Email already exists. Try another one.";
        } else {



        $sql = "UPDATE users SET name = :name, surname = :surname, username = :username, email = :email, phone_number = :phonenumber, address = :address WHERE user_id = :id";


        $sqlPrep = $conn->prepare($sql);

        $sqlPrep->bindParam(':id', $id);
        $sqlPrep->bindParam(':name', $name);
        $sqlPrep->bindParam(':surname', $surname);
        $sqlPrep->bindParam(':username', $username);
        $sqlPrep->bindParam(':email', $email);
        $sqlPrep->bindParam(':phonenumber', $phonenumber);
        $sqlPrep->bindParam(':address', $address);
        

        $sqlPrep->execute();



        
        $redirectUrl = "account.php?action=profile_updated";

        header("Location: " . $redirectUrl);
        exit();

        }
    }    