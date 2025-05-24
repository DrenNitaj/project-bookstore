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


    






    // Prepare the base SQL query for fetching all books
    $sql = "SELECT * FROM books";
    $sqlPrep = $conn->prepare($sql);
    $sqlPrep->execute();
    $books = $sqlPrep->fetchAll();

    // Array mapping category names to their corresponding category IDs
    $categories = [
        'childrens' => 4,
        'fiction' => 1,
        'nonFiction' => 2,
        'youngAdult' => 3,
        'graphicNovelsAndComics' => 5,
        'poetry' => 6,
        'dramaAndPlays' => 7,
        'religiousAndSpiritual' => 8,
        'educationalAndAcademic' => 9,
        'additionalCategories' => 10
    ];

    // Array to store results by category
    $categoryResults = [];

    // Loop through the categories and fetch the books for each category
    foreach ($categories as $categoryName => $categoryId) {
        $sql = "SELECT books.*
                FROM books
                JOIN categories ON books.category_id = categories.category_id
                WHERE categories.category_id = :categoryId";

        $sqlPrep = $conn->prepare($sql);
        $sqlPrep->bindParam(':categoryId', $categoryId);
        $sqlPrep->execute();

        // Store the results in the array with the category name as the key
        $categoryResults[$categoryName] = $sqlPrep->fetchAll();
    }



    $sql = "SELECT * 
            FROM books 
            ORDER BY book_id DESC 
            LIMIT 10";

    $sqlPrep = $conn->prepare($sql);

    $sqlPrep->execute();

    $newBooks = $sqlPrep->fetchAll();
    
    
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
    <title>the BookHouse / Books</title>
    <link rel="icon" href="images/logo.jpg">
</head>
<body>
    
    <header>
		<div class="logo">
            <img src="images/logo1.png" alt="Logo">
            <h1>the BookHouse</h1>
        </div>
		<ul class="ulSignin">
			<li class="active"><a href="signinBooks.php">BOOKS</a></li>
            <li><a href="cart.php">CART</a></li>
            <li><a href="wishlist.php">WISHLIST</a></li>
            <li><a href="purchases.php">PURCHASES</a></li>
            <li><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
            <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>
    

    <div class="custom-alert" id="welcomeAlert">
        <h2 class="welcome-text">Welcome <?php echo $_SESSION['user_name'] ?>!</h2>
        <p>Explore a world of books tailored just for you. Dive in and find your next great read!</p>
    </div>

    <div id="genres">
        <div id="sidebar">
            <div class="sidebar">&#9776;</div>
            <aside>
                <a href="#" class="activee" data-genre="all-books">All books</a>
                <a href="#" data-genre="new-books">New Books</a>
                <a href="#" data-genre="best-sellers">Best Sellers</a>
                <a href="#" data-genre="fiction">Fiction</a>
                <a href="#" data-genre="non-fiction">Non-Fiction</a>
                <a href="#" data-genre="young-adult">Young Adult</a>
                <a href="#" data-genre="children">Children's</a>
                <a href="#" data-genre="graphic-novels-comics">Graphic Novels & Comics</a>
                <a href="#" data-genre="poetry">Poetry</a>
                <a href="#" data-genre="drama-plays">Drama & Plays</a>
                <a href="#" data-genre="religious-spiritual">Religious & Spiritual</a>
                <a href="#" data-genre="educational-academic">Educational & Academic</a>
                <a href="#" data-genre="additional-categories">Additional Categories</a>
            </aside>
        </div>
        
        <div class="light-overlay"></div>

        <main>
            <div class="main activeee" id="all-books">
                <div class="heading-searchBox">
                    <h1 class="heading">All Books</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($books as $book){ 
                        $in_cart = in_array($book['book_id'], $cart_books);
                        $in_wishlist = in_array($book['book_id'], $wishlist_books);
                        $is_out_of_stock = $book['stock_quantity'] <= 0; // Check if the book is out of stock
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock && !$in_cart): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $book['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                            <a href="signinBook.php?book_id=<?php echo $book['book_id'];?>" class="book-card-link">
                                <img src="images/coverimages/<?php echo $book['cover_image_url'];?>" alt="<?php echo $book['title']; ?>">
                            </a>
                            <div class="title-author-price">
                                <div class="title-author">
                                    <h1 title="<?php echo $book['title'];?>"><?php echo $book['title'];?></h1>
                                    <h2><?php echo $book['author_name'];?></h2>
                                </div>
                                <h1 class="price">€ <?php echo $book['price'];?></h1>
                            </div>
                            <div class="cart-wishlist">
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
                            </div>
                            <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div>
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="new-books">
                <div class="heading-searchBox">
                    <h1 class="heading">New Books</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($newBooks as $newBook){
                        $in_cart = in_array($newBook['book_id'], $cart_books);
                        $in_wishlist = in_array($newBook['book_id'], $wishlist_books); 
                        $is_out_of_stock = $newBook['stock_quantity'] <= 0;
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $newBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $newBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $newBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $newBook['title'];?>"><?php echo $newBook['title'];?></h1>
                                        <h2><?php echo $newBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $newBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $newBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $newBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>
                </div>
            </div>
            <div class="main" id="best-sellers">
                <div class="heading-searchBox">
                    <h1 class="heading">Best Sellers</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                </div>
            </div>
            <div class="main" id="fiction">
                <div class="heading-searchBox">
                    <h1 class="heading">Fiction</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['fiction'] as $fictionBook){
                        $in_cart = in_array($fictionBook['book_id'], $cart_books);
                        $in_wishlist = in_array($fictionBook['book_id'], $wishlist_books);
                        $is_out_of_stock = $fictionBook['stock_quantity'] <= 0;
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $fictionBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $fictionBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $fictionBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $fictionBook['title'];?>"><?php echo $fictionBook['title'];?></h1>
                                        <h2><?php echo $fictionBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $fictionBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $fictionBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $fictionBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="non-fiction">
                <div class="heading-searchBox">
                    <h1 class="heading">Young Adult</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['nonFiction'] as $nonFictionBook){
                        $in_cart = in_array($nonFictionBook['book_id'], $cart_books);
                        $in_wishlist = in_array($nonFictionBook['book_id'], $wishlist_books);  
                        $is_out_of_stock = $nonFictionBook['stock_quantity'] <= 0;  
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $nonFictionBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $nonFictionBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $nonFictionBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $nonFictionBook['title'];?>"><?php echo $nonFictionBook['title'];?></h1>
                                        <h2><?php echo $nonFictionBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $nonFictionBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $nonFictionBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $nonFictionBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="young-adult">
                <div class="heading-searchBox">
                    <h1 class="heading">Children's</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['youngAdult'] as $youngAdultBook){
                        $in_cart = in_array($youngAdultBook['book_id'], $cart_books);
                        $in_wishlist = in_array($youngAdultBook['book_id'], $wishlist_books); 
                        $is_out_of_stock = $youngAdultBook['stock_quantity'] <= 0;   
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $youngAdultBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $youngAdultBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $youngAdultBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $youngAdultBook['title'];?>"><?php echo $youngAdultBook['title'];?></h1>
                                        <h2><?php echo $youngAdultBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $youngAdultBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $youngAdultBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $youngAdultBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="children">
                <div class="heading-searchBox">
                    <h1 class="heading">Graphic Novels & Comics</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['childrens'] as $childrensBook){
                        $in_cart = in_array($childrensBook['book_id'], $cart_books);
                        $in_wishlist = in_array($childrensBook['book_id'], $wishlist_books);  
                        $is_out_of_stock = $childrensBook['stock_quantity'] <= 0;  
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $childrensBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $childrensBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $childrensBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $childrensBook['title'];?>"><?php echo $childrensBook['title'];?></h1>
                                        <h2><?php echo $childrensBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $childrensBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $childrensBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $childrensBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="graphic-novels-comics">
                <div class="heading-searchBox">
                    <h1 class="heading">Poetry</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['graphicNovelsAndComics'] as $graphicNovelsAndComicsBook){
                        $in_cart = in_array($graphicNovelsAndComicsBook['book_id'], $cart_books);
                        $in_wishlist = in_array($graphicNovelsAndComicsBook['book_id'], $wishlist_books);
                        $is_out_of_stock = $graphicNovelsAndComicsBook['stock_quantity'] <= 0;    
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $graphicNovelsAndComicsBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $graphicNovelsAndComicsBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $graphicNovelsAndComicsBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $graphicNovelsAndComicsBook['title'];?>"><?php echo $graphicNovelsAndComicsBook['title'];?></h1>
                                        <h2><?php echo $graphicNovelsAndComicsBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $graphicNovelsAndComicsBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $graphicNovelsAndComicsBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $graphicNovelsAndComicsBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="poetry">
                <div class="heading-searchBox">
                    <h1 class="heading">Drama & Plays</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['poetry'] as $poetryBook){
                        $in_cart = in_array($poetryBook['book_id'], $cart_books);
                        $in_wishlist = in_array($poetryBook['book_id'], $wishlist_books);
                        $is_out_of_stock = $poetryBook['stock_quantity'] <= 0;    
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $poetryBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $poetryBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $poetryBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $poetryBook['title'];?>"><?php echo $poetryBook['title'];?></h1>
                                        <h2><?php echo $poetryBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $poetryBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $poetryBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $poetryBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="drama-plays">
                <div class="heading-searchBox">
                    <h1 class="heading">Religious & Spiritual</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['dramaAndPlays'] as $dramaAndPlaysBook){
                        $in_cart = in_array($dramaAndPlaysBook['book_id'], $cart_books);
                        $in_wishlist = in_array($dramaAndPlaysBook['book_id'], $wishlist_books); 
                        $is_out_of_stock = $dramaAndPlaysBook['stock_quantity'] <= 0;   
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $dramaAndPlaysBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $dramaAndPlaysBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $dramaAndPlaysBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $dramaAndPlaysBook['title'];?>"><?php echo $dramaAndPlaysBook['title'];?></h1>
                                        <h2><?php echo $dramaAndPlaysBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $dramaAndPlaysBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $dramaAndPlaysBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $dramaAndPlaysbook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="religious-spiritual">
                <div class="heading-searchBox">
                    <h1 class="heading">Educationl & Academic</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['religiousAndSpiritual'] as $religiousAndSpiritualBook){
                        $in_cart = in_array($religiousAndSpiritualBook['book_id'], $cart_books);
                        $in_wishlist = in_array($religiousAndSpiritualBook['book_id'], $wishlist_books);    
                        $is_out_of_stock = $religiousAndSpiritualBook['stock_quantity'] <= 0;
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $religiousAndSpiritualBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $religiousAndSpiritualBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $religiousAndSpiritualBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $religiousAndSpiritualBook['title'];?>"><?php echo $religiousAndSpiritualBook['title'];?></h1>
                                        <h2><?php echo $religiousAndSpiritualBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $religiousAndSpiritualBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $religiousAndSpiritualBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $religiousAndSpiritualBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="educational-academic">
                <div class="heading-searchBox">
                    <h1 class="heading">Additional Categories</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['educationalAndAcademic'] as $educationalAndAcademicBook){
                        $in_cart = in_array($educationalAndAcademicBook['book_id'], $cart_books);
                        $in_wishlist = in_array($educationalAndAcademicBook['book_id'], $wishlist_books);
                        $is_out_of_stock = $educationalAndAcademicBook['stock_quantity'] <= 0;   
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $educationalAndAcademicBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $educationalAndAcademicBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $educationalAndAcademicBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $educationalAndAcademicBook['title'];?>"><?php echo $educationalAndAcademicBook['title'];?></h1>
                                        <h2><?php echo $educationalAndAcademicBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $educationalAndAcademicBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $educationalAndAcademicBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $educationalAndAcademicBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="additional-categories">
                <div class="heading-searchBox">
                    <h1 class="heading">All Books</h1>
                    <form class="search-form" onsubmit="event.preventDefault(); search();">
                        <input type="text" class="input" placeholder="Search for Books..." onkeyup="search()">
                        <button class="submit" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
                <div class="books">
                    <?php foreach ($categoryResults['additionalCategories'] as $additionalCategoriesBook){
                        $in_cart = in_array($additionalCategoriesBook['book_id'], $cart_books);
                        $in_wishlist = in_array($additionalCategoriesBook['book_id'], $wishlist_books);
                        $is_out_of_stock = $additionalCategoriesBook['stock_quantity'] <= 0;    
                    ?>
                        <div class="book-card">
                            <?php if ($is_out_of_stock): ?>
                                <div class="out-of-stock-overlay" data-book-id="<?php echo $additionalCategoriesBook['book_id'];?>"><h1>Out of Stock</h1></div>
                            <?php endif; ?>
                                <a href="signinBook.php?book_id=<?php echo $additionalCategoriesBook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $additionalCategoriesBook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $additionalCategoriesBook['title'];?>"><?php echo $additionalCategoriesBook['title'];?></h1>
                                        <h2><?php echo $additionalCategoriesBook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $additionalCategoriesBook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <!-- Cart Button -->
                                    <?php if ($in_cart) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Added to Cart</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToCart.php?book_id=<?php echo $additionalCategoriesBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <?php } ?>

                                    <!-- Wishlist Button -->
                                    <?php if ($in_wishlist) { ?>
                                        <a class="added"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Added to Wishlist</a>
                                    <?php } else { ?>
                                        <a class="add" href="addToWishlist.php?book_id=<?php echo $additionalCategoriesBook['book_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                    <?php } ?>
                                </div>
                                <a class="purchase-button" href="purchases.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <h1 id="no-results" style="display: none;" class="heading">No results found</h1>
        </main>
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
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || performance.getEntriesByType('navigation')[0].type === 'back_forward') {
            location.reload();
            }
        });









        document.querySelectorAll('.out-of-stock-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function() {
                var bookId = overlay.getAttribute('data-book-id'); // Get the book_id from data attribute
                window.location.href = `signinBook.php?book_id=${bookId}`;
            });
        });










        let debounceTimeout;

        function search() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                const query = document.querySelector('.activeee .search-form input').value.toLowerCase().trim();
                const books = document.querySelectorAll('.activeee .book-card');
                let resultsFound = false;

                books.forEach(function(book) {
                    const title = book.querySelector('.title-author h1').textContent.toLowerCase();
                    if (query === '' || title.includes(query)) {
                        book.style.display = 'block';
                        resultsFound = true;
                    } else {
                        book.style.display = 'none';
                    }
                });

                // Show or hide the "No results found" message
                const noResultsMessage = document.getElementById('no-results');
                if (resultsFound) {
                    noResultsMessage.style.display = 'none';
                } else {
                    noResultsMessage.style.display = 'block';
                }

                // Recalculate the sidebar height after updating the DOM
                setTimeout(setSidebarHeight, 0);  // Ensure it runs after the DOM update
            }, 300); // Adjust debounce delay as needed
        }

        function setSidebarHeight() {
            const mainElement = document.querySelector('main');
            const sidebarElement = document.getElementById('sidebar');
            const minHeight = 900;  // Set the minimum height in pixels

            if (mainElement && sidebarElement) {
                const mainHeight = mainElement.offsetHeight;
                const finalHeight = Math.max(mainHeight, minHeight);  // Ensure height is at least 900px
                sidebarElement.style.height = finalHeight + 'px';
            }
        }

        window.addEventListener('load', setSidebarHeight);
        window.addEventListener('resize', setSidebarHeight);

        function observeActiveSection() {
            const mainElements = document.querySelectorAll('.main');

            const observer = new MutationObserver((mutationsList) => {
                for (let mutation of mutationsList) {
                    if (mutation.attributeName === 'class') {
                        setSidebarHeight();
                    }
                }
            });

            mainElements.forEach((mainElement) => {
                observer.observe(mainElement, { attributes: true });
            });
        }

        observeActiveSection();  








        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const lightOverlay = document.querySelector('.light-overlay');
            const sidebarToggleBtn = document.querySelector('.sidebar');


            // Sidebar toggle logic
            function toggleSidebar() {
                const isVisible = sidebar.classList.contains('showw');
                sidebar.classList.toggle('showw', !isVisible);
                lightOverlay.classList.toggle('showwww', !isVisible);
            }

            sidebarToggleBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });

            lightOverlay?.addEventListener('click', () => {
                sidebar.classList.remove('showw');
                lightOverlay.classList.remove('showwww');
            });

            // Genre tab switching logic
            // Genre tab switching logic
            document.querySelectorAll('#sidebar aside a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active state from all links
                    document.querySelectorAll('#sidebar aside a').forEach(l => l.classList.remove('activee'));
                    this.classList.add('activee');

                    // Remove active state from all mains
                    document.querySelectorAll('.main').forEach(main => main.classList.remove('activeee'));

                    // Get the target genre and show it
                    const genre = this.getAttribute('data-genre');
                    const targetMain = document.getElementById(genre);
                    targetMain?.classList.add('activeee');

                    // Save state
                    localStorage.setItem('activeLink', genre);
                    localStorage.setItem('activeMain', genre);

                    // Close sidebar
                    sidebar.classList.remove('showw');
                    lightOverlay.classList.remove('showwww');
                });
            });

            // Restore saved state if user is signed in
            const userIsSignedIn = typeof checkUserAuth === 'function' ? checkUserAuth() : true; // mock fallback

            if (userIsSignedIn) {
                const activeLink = localStorage.getItem('activeLink');
                const activeMain = localStorage.getItem('activeMain');

                if (activeLink && activeMain) {
                    document.querySelector(`#sidebar aside a[data-genre="${activeLink}"]`)?.classList.add('activee');
                    document.getElementById(activeMain)?.classList.add('activeee');
                } else {
                    // Default: All Books active
                    document.querySelector('#sidebar aside a[data-genre="all-books"]')?.classList.add('activee');
                    document.getElementById('all-books')?.classList.add('activeee');
                }
            } else {
                resetToDefaultState();
            }

            // Default state reset
            function resetToDefaultState() {
                document.querySelectorAll('#sidebar aside a').forEach(link => link.classList.remove('activee'));
                document.querySelectorAll('.main').forEach(main => main.classList.remove('activeee'));

                document.querySelector('#sidebar aside a[data-genre="all-books"]')?.classList.add('activee');
                document.getElementById('all-books')?.classList.add('activeee');
            }

            // On user sign-in, reset localStorage
            function onUserSignIn() {
                localStorage.removeItem('activeLink');
                localStorage.removeItem('activeMain');
                // handle other sign-in logic here...
            }
        });







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
                url.searchParams.delete('book_id');
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
                url.searchParams.delete('book_id');
                window.history.replaceState({}, document.title, url);
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
    // Debug: Check if the session variable is set
    if (isset($_SESSION['show_welcome_alert'])) {

        // Output the JavaScript code to show the alert
        echo "<script>
                function showAlert() {
                    const alertBox = document.getElementById('welcomeAlert');
                    alertBox.style.display = 'flex'; // Display the alert box

                    // Hide the alert after 8 seconds
                    setTimeout(() => {
                        alertBox.style.display = 'none';
                    }, 4000);
                }

                window.onload = showAlert;
            </script>";

            // Unset the session variable to prevent alert from showing again
            unset($_SESSION['show_welcome_alert']);

        // echo "Welcome alert session variable is set."; // Debugging output
    } else {
        // echo "Welcome alert session variable is not set."; // Debugging output
    }
?>