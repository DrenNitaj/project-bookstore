<?php
    // Include the session check script
    include('sessionCheck.php');

    // connect with database
    include_once("config.php");

    if(empty($_SESSION['admin_id'])){
        header("Location: signinAdmin.php");
    }



    // $sql = "SELECT * FROM categories";

    // $sqlPrep = $conn->prepare($sql);

    // $sqlPrep->execute();

    // $categories = $sqlPrep->fetchAll();



    // $sql = "SELECT * FROM books";

    // $sqlPrep = $conn->prepare($sql);

    // $sqlPrep->execute();

    // $books = $sqlPrep->fetchAll();



    $sql = "SELECT * FROM users";

    $sqlPrep = $conn->prepare($sql);

    $sqlPrep->execute();

    $users = $sqlPrep->fetchAll();


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
    <title>the BookHouse / Users</title>
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
            <li class="active"><a href="users.php">USERS</a></li>
            <!-- <li><a href="user.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>  ACCOUNT</a></li> -->
            <li><a action="logoutAdmin.php" href="logoutAdmin.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>



    <div class="table" style="margin-top: 98.188px;">
        <div class="heading-searchBox">
            <h1 class="heading">Users</h1>
            <input type="text" class="searchBox" placeholder="Search for Users..." onkeyup="search()">
        </div>
        <table id="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user){ ?>
                    <tr class="clickable-row" data-href="user.php?user_id=<?php echo $user['user_id']; ?>">
                        <td data-label="User ID"><?php echo $user['user_id']; ?></td>
                        <td data-label="Name" class="name"><?php echo $user['name']; ?></td>
                        <td data-label="Surname" class="surname"><?php echo $user['surname']; ?></td>
                        <td data-label="Email"><?php echo $user['email']; ?></td>
                        <td data-label="Phone Number"><?php echo $user['phone_number']; ?></td>
                        <td data-label="Address"><?php echo $user['address']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h1 id="no-results" style="display: none;" class="heading">No results found</h1>
    </div>


    <!-- <div class="overlay">
        <div class="dialog">
            <p>Are you sure?</p>
            <div class="buttons">
                <a class="no">No</a>
                <a class="yes">Yes</a>
            </div>
        </div>
    </div> -->






    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.clickable-row');
            rows.forEach(row => {
                row.addEventListener('click', function () {
                    const href = this.getAttribute('data-href');
                    window.location.href = href;
                });
            });
        });




        let debounceTimeout;

        function search() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                const query = document.querySelector('.searchBox').value.toLowerCase().trim();
                const users = document.querySelectorAll('tbody tr');
                let resultsFound = false;

                users.forEach(function(user) {
                    const name = user.querySelector('.name').textContent.toLowerCase();
                    const surname = user.querySelector('.surname').textContent.toLowerCase();
                    if (query === '' || name.includes(query) || surname.includes(query)) {
                        user.style.display = ''; // Show the row by using the default display property
                        resultsFound = true;
                    } else {
                        user.style.display = 'none'; // Hide the row
                    }
                });

                // Show or hide the "No results found" message
                const noResultsMessage = document.getElementById('no-results');
                if (resultsFound) {
                    noResultsMessage.style.display = 'none';
                } else {
                    noResultsMessage.style.display = 'block';
                }

            }, 300); // Adjust debounce delay as needed
        }





        let timeoutDuration = 1800000; // 30 minutes in milliseconds
        let timeout;

        function resetTimeout() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                // No action here, just wait for the next user interaction to reset the timer
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