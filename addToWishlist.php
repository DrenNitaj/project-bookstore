<?php
// Start the session
session_start();

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

if(empty($_SESSION['user_id'])){
    header("Location: signin.php");
}

// Check if the book_id is provided via GET
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

    // Check if the user already has a wishlist
    $wishlist_query = $conn->prepare("SELECT wishlist_id FROM wishlists WHERE user_id = :user_id");
    $wishlist_query->execute(['user_id' => $user_id]);
    $wishlist = $wishlist_query->fetch();

    if (!$wishlist) {
        // If the wishlist doesn't exist, create a new one
        $create_wishlist = $conn->prepare("INSERT INTO wishlists (user_id) VALUES (:user_id)");
        $create_wishlist->execute(['user_id' => $user_id]);
        $wishlist_id = $conn->lastInsertId();
    } else {
        $wishlist_id = $wishlist['wishlist_id'];
    }

    // Check if the book is already in the wishlist
    $item_query = $conn->prepare("SELECT wishlist_item_id FROM wishlist_items WHERE wishlist_id = :wishlist_id AND book_id = :book_id");
    $item_query->execute(['wishlist_id' => $wishlist_id, 'book_id' => $book_id]);
    $item = $item_query->fetch();

    if (!$item) {
        // If the item is not in the wishlist, add it as a new item
        $add_item = $conn->prepare("INSERT INTO wishlist_items (wishlist_id, book_id) VALUES (:wishlist_id, :book_id)");
        $add_item->execute(['wishlist_id' => $wishlist_id, 'book_id' => $book_id]);
    }

    // Redirect back to the previous page
    if (isset($_SERVER['HTTP_REFERER'])) {
        $refererUrl = $_SERVER['HTTP_REFERER'];
        $parsedUrl = parse_url($refererUrl, PHP_URL_PATH);

        if (basename($parsedUrl) == 'signinBooks.php') {
            $redirectUrl = "signinBooks.php?action=added_to_wishlist&book_id=" . $book_id;
        } else {
            $redirectUrl = $_SERVER['HTTP_REFERER'] . "&action=added_to_wishlist";
        }
    } else {
        $redirectUrl = "signinBooks.php?action=added_to_wishlist&book_id=" . $book_id;
    }

    header("Location: " . $redirectUrl);
    exit();


}
?>
