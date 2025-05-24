<?php

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

if(empty($_SESSION['admin_id'])){
    header("Location: signinAdmin.php");
}


if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch book details using PDO
    $sql = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sql->execute();
    $user = $sql->fetch(PDO::FETCH_ASSOC);




    // Assuming $conn is your PDO connection
    $sql = "
    SELECT COUNT(*) as purchase_count
    FROM purchases
    WHERE user_id = :user_id
    ";

    $sqlPrep = $conn->prepare($sql);

    // Bind the user_id parameter
    $sqlPrep->execute(['user_id' => $user_id]);

    // Fetch the count
    $result = $sqlPrep->fetch(PDO::FETCH_ASSOC);

    // Check the purchase count
    $purchaseCount = $result['purchase_count'];

    $hasNoPurchases = ($purchaseCount == 0);



    





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
            <link rel="stylesheet" type="text/css" href="css/admin.css?v=1.1">
            <title>the BookHouse / <?php echo $user['user_id'];?> <?php echo $user['name'];?> <?php echo $user['surname'];?></title>
            <link rel="icon" href="images/logo.jpg">
        </head>
        <body>
            
        <header>
            <div class="logo">
                <img src="images/logo1.png" alt="Logo">
                <h1>the BookHouse</h1>
            </div>
            <ul>
                <li><a href="addBooks.php">BOOKS</a></li>
                <li><a href="purchases.php">PURCHASES</a></li>
                <li><a href="users.php">USERS</a></li>
                <!-- <li><a href="user.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>  ACCOUNT</a></li> -->
                <li><a action="logoutAdmin.php" href="logoutAdmin.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
            </ul>
            <div class="menu-toggle">&#9776;</div>    
        </header>



        <div class="user-table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td><?php echo $user['name'];?></td>
                    </tr>
                    <tr>
                        <td>Surname</td>
                        <td><?php echo $user['surname'];?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo $user['email'];?></td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td><?php echo $user['phone_number'];?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><?php echo $user['address'];?></td>
                    </tr>
                </tbody>
            </table>
            <div id="purchases">
                <h1>Purchases</h1>
                <?php if($hasNoPurchases){ ?>
                    <h1 id="no-purchases" class="heading">No Purchases</h1>
                <?php } else { ?>
                    <div>

                    </div> 
                <?php } ?>       
            </div>
        </div>





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
        <script type="text/javascript" src="js/main.js?v=1.1"></script>
    </body>
    </html>    
        <?php
    } else {
        echo "User not found.";
    }
} else {
    echo "No user ID provided.";
}
?>
