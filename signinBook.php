<?php

// Start the session
session_start();

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

// Include the script to remove expired items
include_once("removeExpiredItems.php");

if(empty($_SESSION['user_id'])){
    header("Location: signin.php");
}


if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details using PDO
    $sql = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id");
    $sql->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $sql->execute();
    $book = $sql->fetch(PDO::FETCH_ASSOC);


    // Assuming the user ID is stored in the session
    $user_id = $_SESSION['user_id'];

    $user_name = $_SESSION['user_name'];

    // Fetch all books in the user's cart
    $cart_query = $conn->prepare("SELECT book_id FROM cart_items ci 
    JOIN shopping_cart sc ON ci.cart_id = sc.cart_id 
    WHERE sc.user_id = :user_id");
    $cart_query->execute(['user_id' => $user_id]);
    $cart_books = $cart_query->fetchAll(PDO::FETCH_COLUMN, 0);

    // Fetch all books in the user's wishlist
    $wishlist_query = $conn->prepare("SELECT book_id FROM wishlist_items wi 
        JOIN wishlists w ON wi.wishlist_id = w.wishlist_id 
        WHERE w.user_id = :user_id");
    $wishlist_query->execute(['user_id' => $user_id]);
    $wishlist_books = $wishlist_query->fetchAll(PDO::FETCH_COLUMN, 0);


    $user_id = $_SESSION['user_id'];

    $sql = "
    SELECT books.book_id, books.title, books.author_name, books.cover_image_url, books.price
    FROM cart_items
    JOIN books ON cart_items.book_id = books.book_id
    JOIN shopping_cart ON cart_items.cart_id = shopping_cart.cart_id
    WHERE shopping_cart.user_id = :user_id
    ORDER BY cart_items.cart_item_id DESC
    LIMIT 1
    ";

    $sqlPrep = $conn->prepare($sql);

    // Correct execution with parameter binding
    $sqlPrep->execute(['user_id' => $user_id]);

    $lastCartItem = $sqlPrep->fetch();


    $user_id = $_SESSION['user_id'];

    $sql = "
        SELECT books.book_id, books.title, books.author_name, books.cover_image_url, books.price
        FROM wishlist_items
        JOIN books ON wishlist_items.book_id = books.book_id
        JOIN wishlists ON wishlist_items.wishlist_id = wishlists.wishlist_id
        WHERE wishlists.user_id = :user_id
        ORDER BY wishlist_items.wishlist_item_id DESC
        LIMIT 1
    ";

    $sqlPrep = $conn->prepare($sql);

    // Correct execution with parameter binding
    $sqlPrep->execute(['user_id' => $user_id]);

    $lastWishlistItem = $sqlPrep->fetch();






    $user_id = $_SESSION['user_id'];

    $sql = "
    SELECT books.book_id, books.title, books.author_name, books.cover_image_url, books.price
    FROM deleted_cart_items
    JOIN books ON deleted_cart_items.book_id = books.book_id
    WHERE deleted_cart_items.user_id = :user_id
    ORDER BY deleted_cart_items.deleted_cart_item_id DESC
    LIMIT 1
    ";

    $sqlPrep = $conn->prepare($sql);

    // Correct execution with parameter binding
    $sqlPrep->execute(['user_id' => $user_id]);

    $lastDeletedCartItem = $sqlPrep->fetch();





    $user_id = $_SESSION['user_id'];

    $sql = "
    SELECT books.book_id, books.title, books.author_name, books.cover_image_url, books.price
    FROM deleted_wishlist_items
    JOIN books ON deleted_wishlist_items.book_id = books.book_id
    WHERE deleted_wishlist_items.user_id = :user_id
    ORDER BY deleted_wishlist_items.deleted_wishlist_item_id DESC
    LIMIT 1
    ";

    $sqlPrep = $conn->prepare($sql);

    // Correct execution with parameter binding
    $sqlPrep->execute(['user_id' => $user_id]);

    $lastDeletedWishlistItem = $sqlPrep->fetch();



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
            <ul class="ulSignin">
                <li><a href="signinBooks.php">BOOKS</a></li>
                <li><a href="cart.php">CART</a></li>
                <li><a href="wishlist.php">WISHLIST</a></li>
                <li><a href="purchase.php">PURCHASES</a></li>
                <li><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
                <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
            </ul>
            <div class="menu-toggle">&#9776;</div>    
        </header>



        <div class="book-details-reviews">
            <div class="book">
                <?php if ($book){ 
                    $in_cart = in_array($book['book_id'], $cart_books);
                    $in_wishlist = in_array($book['book_id'], $wishlist_books); 
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
                            <?php if ($is_out_of_stock && !$in_cart): ?>
                                <p>The other book actions are currently unavaliable.</p>
                                <!-- Wishlist Button -->
                                <?php if ($in_wishlist) { ?>
                                    <a class="added" href="removeFromWishlist.php?book_id=<?php echo $book['book_id']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                            <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                        </svg> 
                                        Remove from Wishlist
                                    </a>
                                <?php } else { ?>
                                    <a class="add" href="addToWishlist.php?book_id=<?php echo $book['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                <?php } ?>
                            <?php else: ?>
                            <!-- Cart Button -->
                            <?php if ($in_cart) { ?>
                                <a class="added" href="removeFromCart.php?book_id=<?php echo $book['book_id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                        <path d="M360-640v-80h240v80H360ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/>
                                    </svg> 
                                    Remove from Cart
                                </a>
                            <?php } else { ?>
                                <a class="add" href="addToCart.php?book_id=<?php echo $book['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                            <?php } ?>

                            <!-- Wishlist Button -->
                            <?php if ($in_wishlist) { ?>
                                <a class="added" href="removeFromWishlist.php?book_id=<?php echo $book['book_id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                        <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                    </svg> 
                                    Remove from Wishlist
                                </a>
                            <?php } else { ?>
                                <a class="add" href="addToWishlist.php?book_id=<?php echo $book['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                            <?php } ?>
                            <a class="purchase-button add" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            
            <form action="addBookReviews.php?book_id=<?php echo $book['book_id']; ?>" method="post">
                <input type="text" class="input" id="review" placeholder="Leave a comment!" name="review" required>
                <button class="submit" type="submit" name="submit"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z"/></svg></button>
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


            <div class="alert" id="review-alert">                         
                <h1>Thank you! Your review was submitted successfully.</h1>
            </div>

            <div class="alert" id="delete-review-alert">                         
                <h1>Your review has been successfully removed.</h1>
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



            <div class="alert" id="cart-alert">
                <img src="images/coverimages/<?php echo $lastCartItem['cover_image_url'];?>" alt="<?php echo $lastCartItem['title']; ?>">
                <div>
                    <h1 title="<?php echo $lastCartItem['title'];?>"><?php echo $lastCartItem['title'];?></h1>                            
                    <h1>Book added to cart</h1>
                </div>
            </div>
            <div class="alert" id="wishlist-alert">
                <img src="images/coverimages/<?php echo $lastWishlistItem['cover_image_url'];?>" alt="<?php echo $lastWishlistItem['title']; ?>">
                <div>
                    <h1 title="<?php echo $lastWishlistItem['title'];?>"><?php echo $lastWishlistItem['title'];?></h1>                            
                    <h1>Book added to wishlist</h1>
                </div>
            </div>


            <div class="alert" id="remove-cart-alert">
                <img src="images/coverimages/<?php echo $lastDeletedCartItem['cover_image_url'];?>" alt="<?php echo $lastDeletedCartItem['title']; ?>">
                <div>
                    <h1 title="<?php echo $lastDeletedCartItem['title'];?>"><?php echo $lastDeletedCartItem['title'];?></h1>                            
                    <h1>Book removed from cart</h1>
                </div>
            </div>
            <div class="alert" id="remove-wishlist-alert">
                <img src="images/coverimages/<?php echo $lastDeletedWishlistItem['cover_image_url'];?>" alt="<?php echo $lastDeletedWishlistItem['title']; ?>">
                <div>
                    <h1 title="<?php echo $lastDeletedWishlistItem['title'];?>"><?php echo $lastDeletedWishlistItem['title'];?></h1>                            
                    <h1>Book removed from wishlist</h1>
                </div>
            </div>






            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check URL parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const action = urlParams.get('action');
                    const bookId = urlParams.get('book_id');

                    if (action === 'added_to_cart' && bookId) {
                        showCartAlert();
                        setTimeout(hideCartAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    } else if (action === 'added_to_wishlist' && bookId) {
                        showWishlistAlert();
                        setTimeout(hideWishlistAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    }

                    function showCartAlert() {
                        const cartAlert = document.getElementById('cart-alert');
                        cartAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideCartAlert() {
                        const cartAlert = document.getElementById('cart-alert');
                        cartAlert.style.display = 'none'; // Hide the alert
                    }

                    function showWishlistAlert() {
                        const wishlistAlert = document.getElementById('wishlist-alert');
                        wishlistAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideWishlistAlert() {
                        const wishlistAlert = document.getElementById('wishlist-alert');
                        wishlistAlert.style.display = 'none'; // Hide the alert
                    }

                    function removeUrlParams() {
                        // Modify the browser's history to remove action and book_id from the URL
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        window.history.replaceState({}, document.title, url);
                    }
                });




                document.addEventListener('DOMContentLoaded', function() {
                    // Check URL parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const action = urlParams.get('action');
                    const bookId = urlParams.get('book_id');

                    if (action === 'removed_from_cart' && bookId) {
                        showCartAlert();
                        setTimeout(hideCartAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    } else if (action === 'removed_from_wishlist' && bookId) {
                        showWishlistAlert();
                        setTimeout(hideWishlistAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    }

                    function showCartAlert() {
                        const cartAlert = document.getElementById('remove-cart-alert');
                        cartAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideCartAlert() {
                        const cartAlert = document.getElementById('remove-cart-alert');
                        cartAlert.style.display = 'none'; // Hide the alert
                    }

                    function showWishlistAlert() {
                        const wishlistAlert = document.getElementById('remove-wishlist-alert');
                        wishlistAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideWishlistAlert() {
                        const wishlistAlert = document.getElementById('remove-wishlist-alert');
                        wishlistAlert.style.display = 'none'; // Hide the alert
                    }

                    function removeUrlParams() {
                        // Modify the browser's history to remove action and book_id from the URL
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        window.history.replaceState({}, document.title, url);
                    }
                });






                document.addEventListener('DOMContentLoaded', function() {
                    // Check URL parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const action = urlParams.get('action');
                    const bookId = urlParams.get('book_id');
                    const userId = urlParams.get('user_id');

                    if (action === 'review-added' && bookId && userId) {
                        showReviewAlert();
                        setTimeout(hideReviewAlert, 3000); // Hide after 3 seconds
                        removeUrlParams(); // Remove parameters from URL
                    }

                    function showReviewAlert() {
                        const reviewAlert = document.getElementById('review-alert');
                        reviewAlert.style.display = 'flex'; // Show the alert
                    }

                    function hideReviewAlert() {
                        const reviewAlert = document.getElementById('review-alert');
                        reviewAlert.style.display = 'none'; // Hide the alert
                    }

                    function removeUrlParams() {
                        // Modify the browser's history to remove action and book_id from the URL
                        const url = new URL(window.location);
                        url.searchParams.delete('action');
                        url.searchParams.delete('user_id');
                        window.history.replaceState({}, document.title, url);
                    }
                });

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
