<?php
// connection with database
include_once("config.php");

include('sessionCheck.php');

// Initialize variables to control the overlay
$showOverlay = false;
$overlayType = ''; // 'user' or 'admin'

// Check if a user is already signed in
if (isset($_SESSION['user_id'])) {
    $showOverlay = true;
    $overlayType = 'user';
} elseif (isset($_SESSION['admin_id'])) {
    $showOverlay = true;
    $overlayType = 'admin';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=1.1">
    <title>the BookHouse / Sign in</title>
    <link rel="icon" href="images/logo.jpg">
</head>
<body>

<?php if ($showOverlay): ?>
    <div class="overlay">
        <div class="dialog">
            <p>
                <?php if ($overlayType == 'user'): ?>
                    You are already signed in as User. Do you want to log out and switch accounts?
                <?php elseif ($overlayType == 'admin'): ?>
                    You are already signed in as Staff Member. Do you want to log out and switch accounts?
                <?php endif; ?>
            </p>
            <div class="buttons">
                <?php if ($overlayType == 'user'): ?>
                    <a class="no" href="signinBooks.php">Continue with current account</a>
                    <a href="logout.php" class="yes">Log out</a>
                <?php elseif ($overlayType == 'admin'): ?>
                    <a class="no" href="addBooks.php">Continue with current account</a>
                    <a href="logoutAdmin.php" class="yes">Log out</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<header>    
    <div class="logo">
        <img src="images/logo1.png" alt="Logo">
        <h1>the BookHouse</h1>
    </div>
    <ul class="ulIndex">
        <li><a href="index.php" target="_blank">HOME</a></li>
        <li><a href="books.php" target="_blank">BOOKS</a></li>
        <li class="active"><a href="signin.php">SIGN IN</a></li>
    </ul>
    <div class="menu-toggle">&#9776;</div>    
</header>

<div id="login">
    <h1>Sign in as User</h1>
</div>

<hr>

<div class="books">
        <div class="form-container">
            <h1 class="sign-heading">Sign in</h1>
            <form action="signinLogic.php" method="post">

                <div class="input-div">
                    <input type="text" class="input" placeholder="Username" name="username" required>
                    <label for="floatingInput">Username</label>
                </div>
                <div class="input-div">
                    <input type="password" class="input password" id="password" placeholder="Password" name="password" required>
                    <label for="password">Password</label>
                    <!-- Default eye icon with a class for easier toggling -->
                    <svg class="toggle-password eye-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000" onclick="togglePasswordVisibility('password', this)">
                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                    </svg>
                </div>
                <button class="submit" type="submit" name="submit">Sign in</button>
                <p class="link">New to the BookHouse? <a href="signup.php" id="show-signup">Register now</a></p>
            </form>
            <a id="adminlink" href="signinAdmin.php">Sign in as Staff Member</a>
        </div>
        <img src="images/books.png" alt="books">
</div>



<script>
    function togglePasswordVisibility(inputId, iconElement) {
        var inputField = document.getElementById(inputId);

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
</script>
<script type="text/javascript" src="js/main.js?v=1.1"></script>
</body>
</html>
