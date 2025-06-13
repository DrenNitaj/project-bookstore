<?php
    include('sessionCheck.php');
    include_once("config.php");
    include_once("removeExpiredItems.php");

    if (empty($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    if (empty($_SESSION['purchase_failed'])) {
        header("Location: signinBooks.php");
        exit();
    } else {
        // Remove the flag so the page can’t be re-accessed
        unset($_SESSION['purchase_failed']);
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the BookHouse / Purchase Confirmed</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/cart-wishlist.css?v=1.1">
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
        <li><a href="purchases.php">PURCHASES</a></li>
        <li><a href="account.php" id="user">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#a0a0a0">
                <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/>
            </svg> MY PROFILE</a>
        </li>
        <li><a href="logout.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#a0a0a0">
                <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/>
            </svg> LOG OUT</a>
        </li>
    </ul>
    <div class="menu-toggle">&#9776;</div>
</header>

<section id="section1" class="purchases_coming_soon">
    <section class="books-contain activee" id="books1">
        <div class="books-container">
            <div class="books-text">
                <i class="fas fa-times-circle" style="font-size: 60px; color: #e74c3c; margin-bottom: 10px;"></i>
                <h1>Purchase Failed</h1>
                <p>Something went wrong with your purchase.<br>Please verify your information and try again.<br>If the problem persists, contact <strong>the BookHouse</strong> support.</p>
            </div>
            <div class="buttons">
                <a href="purchases.php"><button>View My Purchases</button></a>
                <a href="signinBooks.php"><button class="secondary">Continue Shopping</button></a>
            </div>
        </div>
    </section>
</section>






<script>
    // Replace history to prevent navigating back to the form
    history.replaceState(null, "", location.href);
    window.addEventListener("popstate", function () {
        history.pushState(null, "", location.href);
    });




    let timeoutDuration = 1800000; // 30 minutes
    let timeout;

    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            location.reload();
        }, timeoutDuration);
    }

    window.onload = resetTimeout;
    document.onmousemove = resetTimeout;
    document.onkeypress = resetTimeout;
    document.onclick = resetTimeout;
</script>

</body>
</html>
