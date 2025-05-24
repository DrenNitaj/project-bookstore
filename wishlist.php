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

    

    $user_id = $_SESSION['user_id'];

    $sql = "
        SELECT books.book_id, books.title, books.author_name, books.cover_image_url, books.price
        FROM wishlist_items
        JOIN books ON wishlist_items.book_id = books.book_id
        JOIN wishlists ON wishlist_items.wishlist_id = wishlists.wishlist_id
        WHERE wishlists.user_id = :user_id
    ";

    $sqlPrep = $conn->prepare($sql);

    // Correct execution with parameter binding
    $sqlPrep->execute(['user_id' => $user_id]);

    $wishlistItems = $sqlPrep->fetchAll(); 
    
    
    // Check if the cart is empty
    $isWishlistEmpty = empty($wishlistItems);






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
    <link rel="stylesheet" type="text/css" href="css/cart-wishlist.css?v=1.1">
    <title>the BookHouse / Wishlist</title>
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
            <li class="active"><a href="wishlist.php">WISHLIST</a></li>
            <li><a href="purchase.php">PURCHASES</a></li>
            <li><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
            <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>


        
        

    <main>
        <h1 class="heading">Wishlist Items</h1>
        <?php if (!$isWishlistEmpty) { ?>
            <div class="slider-wrapper">
                <button class="slider-btn left-btn" id="prev-btn">&#8249;</button> <!-- Left button -->
                <div class="books" id="wishlist-slider">
                    <?php foreach ($wishlistItems as $wishlistItem){ ?>
                        <div class="book-card">
                            <a href="signinBook.php?book_id=<?php echo $wishlistItem['book_id'];?>" class="book-card-link">
                                <img src="images/coverimages/<?php echo $wishlistItem['cover_image_url'];?>" alt="book1">
                            </a>
                            <div class="title-author-price">
                                <div class="title-author">
                                    <h1 title="<?php echo $wishlistItem['title'];?>"><?php echo $wishlistItem['title'];?></h1>
                                    <h2><?php echo $wishlistItem['author_name'];?></h2>
                                </div>
                                <h1 class="price">€ <?php echo $wishlistItem['price'];?></h1>
                            </div>
                            <div class="cart-wishlist" style="text-align: center;">
                                <a href="removeFromWishlist.php?book_id=<?php echo $wishlistItem['book_id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                        <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                    </svg> 
                                    Remove from Wishlist
                                </a>
                            </div>
                        </div> 
                    <?php } ?>
                </div>
                <button class="slider-btn right-btn" id="next-btn">&#8250;</button> <!-- Right button -->
            </div>       
        <?php } else { ?>
            <h1 id="no-wishlist-items" class="heading">No books in wishlist</h1>
        <?php } ?>
    </main>




    <div class="alert" id="remove-wishlist-alert">
        <img src="images/coverimages/<?php echo $lastDeletedWishlistItem['cover_image_url'];?>" alt="<?php echo $lastDeletedWishlistItem['title']; ?>">
        <div>
            <h1 title="<?php echo $lastDeletedWishlistItem['title'];?>"><?php echo $lastDeletedWishlistItem['title'];?></h1>                            
            <h1>Book removed from wishlist</h1>
        </div>
    </div>


    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
            const bookSlider = document.getElementById('wishlist-slider'); // The slider container
            const bookCards = document.querySelectorAll('.book-card'); // All book cards
            const bookCardWidth = 260.275; // Width of a single book card (including padding and margin)
            const gapBetweenCards = 40; // The gap between book cards

            let currentScrollPosition = 0; // Tracks current scroll position
            let visibleCards = 0; // The number of visible book cards

            // Function to adjust the width of the .books container and justify content
            function adjustBooksWidth() {
                const availableWidth = bookSlider.parentElement.clientWidth; // Width of the slider wrapper
                visibleCards = Math.floor(availableWidth / (bookCardWidth + gapBetweenCards)); // Number of cards that can fit fully visible
                
                // Use the smaller of visibleCards and actual number of cards to calculate width
                const cardsToShow = Math.min(visibleCards, bookCards.length);
                const totalBookWidth = cardsToShow * (bookCardWidth + gapBetweenCards);
                
                if (bookCards.length <= visibleCards) {
                    // If fewer cards than visible space, center them and set width exactly for those cards
                    bookSlider.style.justifyContent = 'center';
                    bookSlider.style.width = `${totalBookWidth}px`;
                } else {
                    // Otherwise, left-align and set width to show only the visible cards (for scrolling)
                    bookSlider.style.justifyContent = 'flex-start';
                    bookSlider.style.width = `${visibleCards * (bookCardWidth + gapBetweenCards)}px`;
                }

                updateButtonStates(); // Make sure buttons update on resize
            }

            // Function to scroll to the next set of book cards
            function scrollToNext() {
                const maxScrollPosition = bookCards.length * (bookCardWidth + gapBetweenCards) - bookSlider.clientWidth;

                // Scroll if we haven't reached the end of the slider
                if (currentScrollPosition < maxScrollPosition) {
                    currentScrollPosition += (bookCardWidth + gapBetweenCards);
                    if (currentScrollPosition > maxScrollPosition) currentScrollPosition = maxScrollPosition;
                    bookSlider.scrollTo({ left: currentScrollPosition, behavior: 'smooth' });
                }

                updateButtonStates(); // Update button state after scroll
            }

            // Function to scroll to the previous set of book cards
            function scrollToPrev() {
                // Scroll if we haven't reached the start of the slider
                if (currentScrollPosition > 0) {
                    currentScrollPosition -= (bookCardWidth + gapBetweenCards);
                    if (currentScrollPosition < 0) currentScrollPosition = 0; // Prevent scrolling beyond the first card
                    bookSlider.scrollTo({ left: currentScrollPosition, behavior: 'smooth' });
                }

                updateButtonStates(); // Update button state after scroll
            }

            // ✅ Function to enable/disable next/prev buttons
            function updateButtonStates() {
                const maxScrollPosition = bookCards.length * (bookCardWidth + gapBetweenCards) - bookSlider.clientWidth;

                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');

                if (currentScrollPosition <= 0) {
                    prevBtn.classList.add('disabled');
                } else {
                    prevBtn.classList.remove('disabled');
                }

                if (currentScrollPosition >= maxScrollPosition) {
                    nextBtn.classList.add('disabled');
                } else {
                    nextBtn.classList.remove('disabled');
                }
            }

            // Event listeners for buttons
            document.getElementById('next-btn').addEventListener('click', scrollToNext);
            document.getElementById('prev-btn').addEventListener('click', scrollToPrev);

            // Adjust the books container width on page load and window resize
            window.addEventListener('resize', adjustBooksWidth);
            adjustBooksWidth();
        });









        document.addEventListener('DOMContentLoaded', function() {
            // Check URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            const bookId = urlParams.get('book_id');

            if (action === 'removed_from_wishlist' && bookId) {
                showWishlistAlert();
                setTimeout(hideWishlistAlert, 3000); // Hide after 3 seconds
                removeUrlParams(); // Remove parameters from URL
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