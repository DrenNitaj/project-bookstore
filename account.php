<?php

    // Include the session check script
    include('sessionCheck.php');

    // Connect with the database
    include_once("config.php");

    if (empty($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch user details
    $sql = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sql->execute();
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    // Fetch total number of purchases
    $sql = "SELECT COUNT(*) as purchase_count FROM purchases WHERE user_id = :user_id";
    $sqlPrep = $conn->prepare($sql);
    $sqlPrep->execute(['user_id' => $user_id]);
    $result = $sqlPrep->fetch(PDO::FETCH_ASSOC);
    $purchaseCount = $result['purchase_count'];
    $hasNoPurchases = ($purchaseCount == 0);

    // Fetch breakdown of purchase statuses
    $sql = "
        SELECT 
            COUNT(*) AS total,
            SUM(status = 'processed') AS processed,
            SUM(status = 'completed') AS completed,
            SUM(status = 'failed') AS failed,
            SUM(status = 'declined') AS declined,
            SUM(status = 'refunded') AS refunded
        FROM purchases
        WHERE user_id = :user_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $purchaseStats = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch all reviews by the logged-in user
    $sql = $conn->prepare("
        SELECT 
            reviews.review_id, 
            reviews.book_id, 
            reviews.comment, 
            reviews.review_date, 
            books.title 
        FROM reviews 
        JOIN books ON reviews.book_id = books.book_id 
        WHERE reviews.user_id = :user_id
    ");
    $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sql->execute();
    $userReviews = $sql->fetchAll(PDO::FETCH_ASSOC);


        
    
    
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
            <title>the BookHouse / My Profile</title>
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
                <li><a href="purchases.php">PURCHASES</a></li>
                <li class="active"><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
                <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
            </ul>
            <div class="menu-toggle">&#9776;</div>    
        </header>
    
    

        <h1 class="profile-title">Profile Info</h1>
    
        <div class="user-table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
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
                        <td>Username</td>
                        <td><?php echo $user['username'];?></td>
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
                <tfoot>
                    <tr>
                        <td></td> <!-- Empty cell for the first column -->
                        <td>
                            <div class="edit-profile-container">
                                <a id="editProfileBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                        <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                    </svg>
                                    Edit
                                </a>
                                <div class="profile-actions">
                                    <a class="editProfileLink" href="changePassword.php?user_id=<?php echo $user['user_id']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg>
                                        Change password
                                    </a>
                                    <a class="editProfileLink" href="editProfile.php?user_id=<?php echo $user['user_id']; ?>">
                                        <svg fill="black" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                                        Update profile
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            
            <div id="accountInfo">
                <div id="accountInfoHeader"></div>

                
                <a class="purchases-link" href="purchases.php">
                    <div id="purchases">
                        <h1>Purchases</h1>

                        <?php if ($hasNoPurchases): ?>
                            <h3 id="no-purchases" class="heading">No Purchases</h3>
                        <?php else: ?>
                            <h3 class="purchase-stats">Total Purchases: <?= $purchaseStats['total'] ?></h3>
                            <h3 class="purchase-stats">Processed: <?= $purchaseStats['processed'] ?></h3>
                            <h3 class="purchase-stats">Completed: <?= $purchaseStats['completed'] ?></h3>
                            <h3 class="purchase-stats">Failed: <?= $purchaseStats['failed'] ?></h3>
                            <h3 class="purchase-stats">Declined: <?= $purchaseStats['declined'] ?></h3>
                            <h3 class="purchase-stats">Refunded: <?= $purchaseStats['refunded'] ?></h3>
                        <?php endif; ?>
                    </div>
                </a>

                <div id="lastLogin">
                    <h1>Last login</h1>
                    <h3><?php echo $user['last_login'];?></h3>    
                </div>
            </div>
        </div>





        <div class="user-reviews-section">
            <h1 class="profile-title">Your Comments</h1>

            <?php if (empty($userReviews)) { ?>
                <h1 id="no-comments">No comments yet!</h1>
            <?php } else { ?>
                <div class="reviews-container">
                    <?php foreach ($userReviews as $review) { ?>
                        <div class="review">
                            <div class="review-header">
                                <span class="review-user">Book: <?php echo htmlspecialchars($review['title']); ?></span>
                                <span class="review-date"><?php echo htmlspecialchars($review['review_date']); ?></span>
                            </div>
                            <p class="review-comment">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                            <a href="#" class="delete-review" data-review-id="<?php echo htmlspecialchars($review['review_id']); ?>" data-book-id="<?php echo htmlspecialchars($review['book_id']); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                </svg>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>




        <div class="alert" id="delete-review-alert">                         
            <h1>Your review has been successfully removed.</h1>
        </div>


        <div class="alert" id="updated-profile-alert">                         
            <h1>Profile updated successfully.</h1>
        </div>

        <div class="alert" id="new-password-alert">                         
            <h1>Password changed successfully.</h1>
        </div>



        <div class="overlay">
            <div class="dialog">
                <p>Are you sure?</p>
                <div class="buttons">
                    <a class="no">No</a>
                    <a class="yes">Yes</a>
                </div>
            </div>
        </div>
    
    
    
    
    
        <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check URL parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const action = urlParams.get('action');

                    if (action === 'profile_updated') {
                        showUpdatedProfileAlert();
                        setTimeout(hideUpdatedProfileAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    }

                    function showUpdatedProfileAlert() {
                        const updatedProfileAlert = document.getElementById('updated-profile-alert');
                        updatedProfileAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideUpdatedProfileAlert() {
                        const updatedProfileAlert = document.getElementById('updated-profile-alert');
                        updatedProfileAlert.style.display = 'none'; // Hide the alert
                    }

                    function removeUrlParams() {
                        // Modify the browser's history to remove action from the URL
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        window.history.replaceState({}, document.title, url);
                    } 
                    
                    if (action === 'password_changed') {
                        showNewPasswordAlert();
                        setTimeout(hideNewPasswordAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    }

                    function showNewPasswordAlert() {
                        const newPasswordAlert = document.getElementById('new-password-alert');
                        newPasswordAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideNewPasswordAlert() {
                        const newPasswordAlert = document.getElementById('new-password-alert');
                        newPasswordAlert.style.display = 'none'; // Hide the alert
                    }

                    function removeUrlParams() {
                        // Modify the browser's history to remove action from the URL
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        window.history.replaceState({}, document.title, url);
                    }
                });





                document.addEventListener('DOMContentLoaded', function () {
                    const urlParams = new URLSearchParams(window.location.search);
                    const action = urlParams.get('action');
                    const bookId = urlParams.get('book_id');

                    if (action === 'review-deleted' && bookId) {
                        showReviewAlert();
                        setTimeout(hideReviewAlert, 3000);
                        removeUrlParams();
                    }

                    function showReviewAlert() {
                        const reviewAlert = document.getElementById('delete-review-alert');
                        if (reviewAlert) reviewAlert.style.display = 'flex';
                    }

                    function hideReviewAlert() {
                        const reviewAlert = document.getElementById('delete-review-alert');
                        if (reviewAlert) reviewAlert.style.display = 'none';
                    }

                    function removeUrlParams() {
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        url.searchParams.delete('book_id');
                        window.history.replaceState({}, document.title, url);
                    }

                    // Delete confirmation
                    const overlay = document.querySelector('.overlay');
                    const deleteReviewBtns = document.querySelectorAll('.delete-review');
                    const noLink = document.querySelector('.no');
                    const yesLink = document.querySelector('.yes');

                    let currentReviewId = null;
                    let currentBookId = null;

                    deleteReviewBtns.forEach(link => {
                        link.addEventListener('click', function (event) {
                            event.preventDefault();
                            currentReviewId = this.getAttribute('data-review-id');
                            currentBookId = this.getAttribute('data-book-id');
                            overlay.classList.add('showww');
                        });
                    });

                    if (noLink) {
                        noLink.addEventListener('click', function () {
                            overlay.classList.remove('showww');
                        });
                    }

                    if (yesLink) {
                        yesLink.addEventListener('click', function () {
                            if (currentReviewId) {
                                window.location.href = `deleteReviews.php?review_id=${currentReviewId}&book_id=${currentBookId}`;
                            }
                        });
                    }
                });





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


