<?php

    // connect with database
    include_once("config.php");

    $sql = "SELECT * FROM books";

    $sqlPrep = $conn->prepare($sql);

    $sqlPrep->execute();

    $books = $sqlPrep->fetchAll();



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
		<ul class="ulIndex">
			<li><a href="index.php" target="_blank">HOME</a></li>
            <li class="active"><a href="books.php" target="_blank">BOOKS</a></li>
            <li><a href="signin.php" target="_blank">SIGN IN</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>
    

    <!-- <div class="welcomeMessage">
        <h1 class="welcome-text">Welcome! <span>Explore a world of books tailored just for you. Dive in and find your next great read!</span></h1>
    </div> -->

    <div id="genres" style="margin-top: 96.188px;">
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
                    <?php foreach ($books as $book){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $book['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $book['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $book['title'];?>"><?php echo $book['title'];?></h1>
                                        <h2><?php echo $book['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $book['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="new-books">
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
                    <?php foreach ($newBooks as $newBook){ ?>
                        <div class="book-card">   
                                <a href="book.php?book_id=<?php echo $newBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>   
                </div>
            </div>
            <div class="main" id="best-sellers">
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
                </div>
            </div>
            <div class="main" id="fiction">
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
                    <?php foreach ($categoryResults['fiction'] as $fictionBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $fictionBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="non-fiction">
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
                    <?php foreach ($categoryResults['nonFiction'] as $nonFictionBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $nonFictionBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="young-adult">
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
                    <?php foreach ($categoryResults['youngAdult'] as $youngAdultBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $youngAdultBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="children">
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
                    <?php foreach ($categoryResults['childrens'] as $childrensbook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $childrensbook['book_id'];?>" class="book-card-link">
                                    <img src="images/coverimages/<?php echo $childrensbook['cover_image_url'];?>" alt="book1">
                                </a>
                                <div class="title-author-price">
                                    <div class="title-author">
                                        <h1 title="<?php echo $childrensbook['title'];?>"><?php echo $childrensbook['title'];?></h1>
                                        <h2><?php echo $childrensbook['author_name'];?></h2>
                                    </div>
                                    <h1 class="price">€ <?php echo $childrensbook['price'];?></h1>
                                </div>
                                <div class="cart-wishlist">
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="graphic-novels-comics">
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
                    <?php foreach ($categoryResults['graphicNovelsAndComics'] as $graphicNovelsAndComicsBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $graphicNovelsAndComicsBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="poetry">
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
                    <?php foreach ($categoryResults['poetry'] as $poetryBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $poetryBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="drama-plays">
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
                    <?php foreach ($categoryResults['dramaAndPlays'] as $dramaAndPlaysBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $dramaAndPlaysBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="religious-spiritual">
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
                    <?php foreach ($categoryResults['religiousAndSpiritual'] as $religiousAndSpiritualBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $religiousAndSpiritualBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <div class="main" id="educational-academic">
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
                    <?php foreach ($categoryResults['educationalAndAcademic'] as $educationalAndAcademicBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $educationalAndAcademicBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
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
                    <?php foreach ($categoryResults['additionalCategories'] as $additionalCategoriesBook){ ?>
                        <div class="book-card">
                                <a href="book.php?book_id=<?php echo $additionalCategoriesBook['book_id'];?>" class="book-card-link">
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
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"/></svg> Add to Cart</a>
                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg> Add to Wishlist</a>
                                </div>
                                <a class="purchase-button" href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg> Purchase</a>
                        </div> 
                    <?php } ?>       
                </div>
            </div>
            <h1 id="no-results" style="display: none;" class="heading">No results found</h1>
        </main>
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
        document.querySelectorAll('.out-of-stock-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function() {
                var bookId = overlay.getAttribute('data-book-id'); // Get the book_id from data attribute
                window.location.href = `book.php?book_id=${bookId}`;
            });
        });




        const cartWishlistLinks = document.querySelectorAll('.cart-wishlist a');

        cartWishlistLinks.forEach(link => {
            link.classList.add('add');
        });




        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.querySelector('.overlay');
            const noLink = document.querySelector('.no');
            const yesLink = document.querySelector('.yes');
            const sidebar = document.getElementById('sidebar');
            const lightOverlay = document.querySelector('.light-overlay');
            const sidebarToggleBtn = document.querySelector('.sidebar');

            // Auth overlay
            function showOverlay(event) {
                event.preventDefault();
                overlay.classList.add('showww');
            }

            function hideOverlay() {
                overlay.classList.remove('showww');
            }

            noLink?.addEventListener('click', hideOverlay);
            yesLink?.addEventListener('click', hideOverlay);

            document.querySelectorAll('.cart-wishlist a, .purchase-button').forEach(button => {
                button.addEventListener('click', showOverlay);
            });

            console.log('sidebar:', sidebar);


            // Sidebar toggle
            function toggleSidebar() {
                if (sidebar.classList.contains('showw')) {
                    sidebar.classList.remove('showw');
                    lightOverlay.classList.remove('showwww');
                } else {
                    sidebar.classList.add('showw');
                    lightOverlay.classList.add('showwww');
                }
            }

            sidebarToggleBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });

            lightOverlay?.addEventListener('click', () => {
                sidebar.classList.remove('showw');
                lightOverlay.classList.remove('showwww');
            });

            // Genre tab switching
            document.querySelectorAll('#sidebar aside a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelectorAll('#sidebar aside a').forEach(link =>
                        link.classList.remove('activee')
                    );
                    this.classList.add('activee');

                    const genre = this.getAttribute('data-genre');
                    localStorage.setItem('activeLink', genre);
                    localStorage.setItem('activeMain', genre);

                    document.querySelectorAll('.main').forEach(main =>
                        main.classList.remove('activeee')
                    );
                    document.getElementById(genre)?.classList.add('activeee');

                    sidebar.classList.remove('showw');
                    lightOverlay.classList.remove('showwww');
                });
            });

            // Restore last active state
            const activeLink = localStorage.getItem('activeLink');
            const activeMain = localStorage.getItem('activeMain');

            document.querySelector('#sidebar aside a[data-genre="all-books"]')?.classList.remove('activee');
            document.getElementById('all-books')?.classList.remove('activeee');

            if (activeLink) {
                document.querySelector(`#sidebar aside a[data-genre="${activeLink}"]`)?.classList.add('activee');
            }

            if (activeMain) {
                document.getElementById(activeMain)?.classList.add('activeee');
            }
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
    </script>
    <script type="text/javascript" src="js/main.js?v=1.1"></script>
</body>
</html>