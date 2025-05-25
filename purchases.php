<?php

    // Include the session check script
    include('sessionCheck.php');

    // connect with database
    include_once("config.php");

    // Include the script to remove expired items
    include_once("removeExpiredItems.php");

    if(empty($_SESSION['user_id'])){
        header("Location: signin.php");
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" type="text/css" href="css/cart-wishlist.css?v=1.1">
    <title>the BookHouse / Purchases</title>
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
            <li class="active"><a href="purchases.php">PURCHASES</a></li>
            <li><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
            <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>


        
        

    <section id="section1" class="purchases_coming_soon">
        <section class="books-contain activee" id="books1">
            <div class="books-container">
                <div class="books-text">
                    <h1>Purchases Coming Soon</h1>
                    <p>We're preparing a better way for you to buy books. Stay tuned!</p>
                </div>
                <div class="buttons">
                    <a href="signinBooks.php"><button>Browse for Books</button></a>
                </div>
            </div>
        </section>
    </section>




    


    <script>
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
    <!-- <script type="text/javascript" src="js/main.js?v=1.1"></script> -->
</body>
</html>