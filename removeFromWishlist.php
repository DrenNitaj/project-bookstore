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
    $user_id = $_SESSION['user_id'];

    // SQL to get the wishlist ID
    $wishlist_query = $conn->prepare("SELECT wishlist_id FROM wishlists WHERE user_id = :user_id");
    $wishlist_query->execute(['user_id' => $user_id]);
    $wishlist = $wishlist_query->fetch();

    if ($wishlist) {
        $wishlist_id = $wishlist['wishlist_id'];

        // Check if the book is in the wishlist
        $item_query = $conn->prepare("SELECT wishlist_item_id FROM wishlist_items WHERE wishlist_id = :wishlist_id AND book_id = :book_id");
        $item_query->execute(['wishlist_id' => $wishlist_id, 'book_id' => $book_id]);
        $item = $item_query->fetch();

        if ($item) {
            // Save the deleted item details BEFORE deleting it from wishlist_items
            $save_deleted_item = $conn->prepare("
                INSERT INTO deleted_wishlist_items (user_id, book_id, wishlist_item_id)
                VALUES (:user_id, :book_id, :wishlist_item_id)
            ");
            $save_deleted_item->execute([
                'user_id' => $user_id,
                'book_id' => $book_id,
                'wishlist_item_id' => $item['wishlist_item_id']
            ]);

            // Now safely remove the item from the wishlist
            $delete_item = $conn->prepare("DELETE FROM wishlist_items WHERE wishlist_item_id = :wishlist_item_id");
            $delete_item->execute(['wishlist_item_id' => $item['wishlist_item_id']]);

            // After deletion, remove or nullify wishlist_item_id from deleted_wishlist_items
            $update_deleted_item = $conn->prepare("
                UPDATE deleted_wishlist_items SET wishlist_item_id = NULL WHERE wishlist_item_id = :wishlist_item_id
            ");
            $update_deleted_item->execute(['wishlist_item_id' => $item['wishlist_item_id']]);
        }
    }

    // Redirect back to the previous page
    if (isset($_SERVER['HTTP_REFERER'])) {
        $refererUrl = $_SERVER['HTTP_REFERER'];
        $parsedUrl = parse_url($refererUrl, PHP_URL_PATH);

        if (basename($parsedUrl) == 'signinBooks.php') {
            $redirectUrl = "signinBooks.php?action=removed_from_wishlist&book_id=" . $book_id;
        } else if (basename($parsedUrl) == 'wishlist.php') {
            $redirectUrl = "wishlist.php?action=removed_from_wishlist&book_id=" . $book_id;
        } else {
            $redirectUrl = $_SERVER['HTTP_REFERER'] . "&action=removed_from_wishlist";
        }
    } else {
        $redirectUrl = "signinBooks.php?action=removed_from_wishlist&book_id=" . $book_id;
    }

    header("Location: " . $redirectUrl);
    exit();
} else {
    // Redirect to the wishlist page if no book_id is provided
    header("Location: wishlist.php");
    exit;
}
?>
