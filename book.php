<?php

// Include database connection
include_once("config.php");


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
                <ul class="ulIndex">
                    <li><a href="index.php" target="_blank">HOME</a></li>
                    <li><a href="books.php" target="_blank">BOOKS</a></li>
                    <li><a href="signin.php" target="_blank">SIGN IN</a></li>
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
                        <div class="book-actions">
                            <a href="#" class="add"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                            <a href="#" class="add"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                            <a class="purchase-button add" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div>
                    </div>
                <?php } ?>
            </div> 




            <form action="addBookReviews.php?book_id=<?php echo $book['book_id']; ?>" method="post">
                <input id="review" type="text" class="input" id="review" placeholder="Leave a comment!" name="review" required readonly>
                <button id="reviewSubmitBtn" class="submit" type="submit" name="submit"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z"/></svg></button>
            </form>
            
            
            <?php if (empty($reviews)) { ?>
                <h1 id="no-comments">No comments yet!</h1>
            <?php } else { ?>
                <div class="reviews-container">
                    <?php foreach ($reviews as $review) { ?>
                        <div class="review">
                            <div class="review-header">
                                <span class="review-user"><?php echo htmlspecialchars($review['name'] . ' ' . $review['surname']); ?></span>
                                <span class="review-date"><?php echo htmlspecialchars($review['review_date']); ?></span>
                            </div>
                            <p class="review-comment">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']) { ?>
                                <a href="#" class="delete-review" data-review-id="<?php echo htmlspecialchars($review['review_id']); ?>" data-book-id="<?php echo htmlspecialchars($review['book_id']); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                    </svg>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>    



            <div class="overlay">
                <div class="dialog">
                    <p>You have to sign in first!</p>
                    <div class="buttons">
                        <a class="no">Not now</a>
                        <a href="signin.php" class="yes">Sign in</a>
                    </div>
                </div>
            </div>






            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const overlay = document.querySelector('.overlay');
                    const noLink = document.querySelector('.no');
                    const yesLink = document.querySelector('.yes');
                    const reviewInput = document.querySelector('#review');
                    const reviewSubmitBtn = document.querySelector('#reviewSubmitBtn');

                    function showOverlay(event) {
                        event.preventDefault(); // Prevent default action of the link
                        overlay.classList.add('showww');
                    }

                    function hideOverlay() {
                        overlay.classList.remove('showww');
                    }

                    // Event listeners for the overlay buttons
                    noLink.addEventListener('click', hideOverlay);
                    yesLink.addEventListener('click', hideOverlay); // Redirect to sign-in will automatically hide the overlay

                    // Event listeners for buttons that should show the overlay
                    document.querySelectorAll('.book a').forEach(button => {
                        button.addEventListener('click', showOverlay);
                    });

                    // Event listener for the input field to show the overlay
                    reviewInput.addEventListener('click', showOverlay);
                    // Event listener for the input submit btn field to show the overlay
                    reviewSubmitBtn.addEventListener('click', showOverlay);
                });
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
