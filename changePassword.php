<?php

    // Include the session check script
    include('sessionCheck.php');

    // connect with database
    include_once("config.php");

    if(empty($_SESSION['user_id'])){
        header("Location: signin.php");
    }


    $user_id = $_SESSION['user_id'];
    
    // Fetch book details using PDO
    $sql = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sql->execute();
    $user = $sql->fetch(PDO::FETCH_ASSOC);
        
    
    
    if ($user) {
?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
            <link rel="stylesheet" type="text/css" href="css/user.css?v=1.1">
            <title>the BookHouse / Edit Profile</title>
            <link rel="icon" href="images/logo.jpg">
        </head>
        <body>
                
        <header>
            <div class="logo">
                <img src="images/logo1.png" alt="Logo">
                <h1>the BookHouse</h1>
            </div>
            <ul class="ulSignin">
                <li><a href="signinBooks.php">BOOKS</a></li>
                <li><a href="cart.php">CART</a></li>
                <li><a href="wishlist.php">WISHLIST</a></li>
                <li><a href="purchase.php">PURCHASES</a></li>
                <li class="active"><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
                <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
            </ul>
            <div class="menu-toggle">&#9776;</div>    
        </header>
    
    
        <h1 class="profile-title">Change Password</h1>
    
        <form action="changePasswordLogic.php" method="post">
            <div style="padding-bottom: 60px;" class="user-table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Set new password</td>
                            <td>    
                                <div class="input-div">
                                    <input type="password" id="new_password" class="input" placeholder="New password" name="new_password" value="" required>
                                    <!-- Default eye icon with a class for easier toggling -->
                                    <svg class="toggle-password eye-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" onclick="togglePasswordVisibility('new_password', this)">
                                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Confirm new password</td>
                            <td>    
                                <div class="input-div">
                                    <input type="password" id="confirmed_new_password" class="input" placeholder="Confirm new password" name="confirmed_password" value="" required>
                                    <!-- Default eye icon with a class for easier toggling -->
                                    <svg class="toggle-password eye-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" onclick="togglePasswordVisibility('confirmed_new_password', this)">
                                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td> <!-- Empty cell for the first column -->
                            <td>
                                <button class="submit" type="submit" name="submit"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>Confirm</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>

    
    
    
    
    
        <script>
            function togglePasswordVisibility(inputId, iconElement) {
                var inputField = document.getElementById(inputId); // Corrected this line

                // Toggle password visibility
                if (inputField.type === "password") {
                    inputField.type = "text"; // Show password
                    iconElement.classList.add('eye-slash'); // Add the eye-slash class
                    iconElement.classList.remove('eye-icon'); // Remove the eye class
                } else {
                    inputField.type = "password"; // Hide password
                    iconElement.classList.add('eye-icon'); // Add the eye class
                    iconElement.classList.remove('eye-slash'); // Remove the eye-slash class
                }

                // Prevent the input field from losing focus
                inputField.focus();
            }   





            let timeoutDuration = 1800000; // 30 minutes in milliseconds
            let timeout;
    
            function resetTimeout() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    location.reload();
                }, timeoutDuration);
            }
    
            // Detect user activities
            window.onload = resetTimeout; // Reset timeout on page load
            document.onmousemove = resetTimeout; // Reset timeout on mouse movement
            document.onkeypress = resetTimeout; // Reset timeout on key press
            document.onclick = resetTimeout; // Reset timeout on click
        </script>
        <script type="text/javascript" src="js/main.js?v=1.1"></script>
        </body>
    </html>    
<?php
    } else {
        echo "No user ID provided.";
    }
?>


