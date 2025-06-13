<?php

// Start the session
session_start();

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

if(empty($_SESSION['admin_id'])){
    header("Location: signinAdmin.php");
}


if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details using PDO
    $sql = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id");
    $sql->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $sql->execute();
    $book = $sql->fetch(PDO::FETCH_ASSOC);



    $sql = $conn->prepare("
        SELECT reviews.review_id, reviews.user_id, reviews.book_id, reviews.comment, reviews.review_date, users.name, users.surname 
        FROM reviews 
        JOIN users ON reviews.user_id = users.user_id 
        WHERE reviews.book_id = :book_id
    ");

    $sql->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $sql->execute();

    $reviews = $sql->fetchAll(PDO::FETCH_ASSOC);





    if ($book) {
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
            <link rel="stylesheet" type="text/css" href="css/book.css?v=1.1">
            <title>the BookHouse / <?php echo $book['title'];?></title>
            <link rel="icon" href="images/logo.jpg">
        </head>
        <body>
            
        <header>
            <div class="logo">
                <img src="images/logo1.png" alt="Logo">
                <h1>the BookHouse</h1>
            </div>
            <ul>
                <li class="active"><a href="addBooks.php">BOOKS</a></li>
                <li><a href="purchasesAdmin.php">PURCHASES</a></li>
                <li><a href="users.php">USERS</a></li>
                <!-- <li><a href="user.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>  ACCOUNT</a></li> -->
                <li><a action="logoutAdmin.php" href="logoutAdmin.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
            </ul>
            <div class="menu-toggle">&#9776;</div>    
        </header>



        <div class="book-details-reviews">
            <div class="book">
                <?php if ($book){ 
                    $is_out_of_stock = $book['stock_quantity'] <= 0;   
                ?>
                    <h1 class="title" title="<?php echo $book['title'];?>"><?php echo $book['title'];?></h1>
                    <h2 class="author"><?php echo $book['author_name'];?></h2>
                    <div class="book-image">
                        <?php if ($is_out_of_stock): ?>
                            <div class="out-of-stock-overlay"><h1>Out of Stock</h1></div>
                        <?php endif; ?>
                        <img src="images/coverimages/<?php echo $book['cover_image_url'];?>" alt="book1">
                    </div>
                    <div class="book-details">
                        <h1 title="<?php echo $book['title'];?>"><?php echo $book['title'];?></h1>
                        <h2><?php echo $book['author_name'];?></h2>
                        <p class="description"><?php echo $book['description'];?></p>
                        <div class="price-stock">
                            <div class="price-div">
                                <h2>Price:</h2>
                                <h2 class="price">€ <?php echo $book['price'];?></h2>
                            </div>
                            <div class="stock-div">
                                <h2>Stock Quantity:</h2>
                                <h2 class="stock-quantity"><?php echo $book['stock_quantity'];?></h2>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            
            <?php if (empty($reviews)) { ?>
                <h1 id="no-comments">No comments yet!</h1>
            <?php } else { ?>
                <div class="reviews-container">
                    <?php foreach ($reviews as $review) { ?>
                        <div class="review">
                            <div class="review-header">
                                <span class="review-user"><?php echo $review['name'] . ' ' . $review['surname']; ?></span>
                                <span class="review-date"><?php echo $review['review_date']; ?></span>
                            </div>
                            <p class="review-comment">"<?php echo $review['comment']; ?>"</p>
                            <a href="#" class="delete-review" data-review-id="<?php echo $review['review_id']; ?>" data-book-id="<?php echo $review['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

             
        </div> 


            <div class="alert" id="delete-review-alert">                         
                <h1>Review has been successfully removed.</h1>
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
                    const bookId = urlParams.get('book_id');

                    if (action === 'review-deleted' && bookId) {
                        showReviewAlert();
                        setTimeout(hideReviewAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    }

                    function showReviewAlert() {
                        const reviewAlert = document.getElementById('delete-review-alert');
                        reviewAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideReviewAlert() {
                        const reviewAlert = document.getElementById('delete-review-alert');
                        reviewAlert.style.display = 'none'; // Hide the alert
                    }

                    function removeUrlParams() {
                        // Modify the browser's history to remove action and book_id from the URL
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        window.history.replaceState({}, document.title, url);
                    }
                });



                
                document.addEventListener('DOMContentLoaded', function () {
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

                    noLink.addEventListener('click', function () {
                        overlay.classList.remove('showww');
                    });

                    yesLink.addEventListener('click', function () {
                        if (currentReviewId) {
                            window.location.href = `deleteReviews.php?review_id=${currentReviewId}&book_id=${currentBookId}`;
                        }
                    });
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
        echo "Book not found.";
    }
} else {
    echo "No book ID provided.";
}
?>
