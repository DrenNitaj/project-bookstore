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

    // SQL to get the cart ID
    $cart_query = $conn->prepare("SELECT cart_id FROM shopping_cart WHERE user_id = :user_id");
    $cart_query->execute(['user_id' => $user_id]);
    $cart = $cart_query->fetch();

    if ($cart) {
        $cart_id = $cart['cart_id'];

        // Check if the book is in the cart
        $item_query = $conn->prepare("SELECT cart_item_id FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id");
        $item_query->execute(['cart_id' => $cart_id, 'book_id' => $book_id]);
        $item = $item_query->fetch();

        if ($item) {
            // Save the deleted item details BEFORE deleting it from cart_items
            $save_deleted_item = $conn->prepare("
                INSERT INTO deleted_cart_items (user_id, book_id, cart_item_id)
                VALUES (:user_id, :book_id, :cart_item_id)
            ");
            $save_deleted_item->execute([
                'user_id' => $user_id,
                'book_id' => $book_id,
                'cart_item_id' => $item['cart_item_id']
            ]);

            // Now safely remove the item from the cart
            $delete_item = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = :cart_item_id");
            $delete_item->execute(['cart_item_id' => $item['cart_item_id']]);

            // After deletion, remove or nullify cart_item_id from deleted_cart_items
            $update_deleted_item = $conn->prepare("
                UPDATE deleted_cart_items SET cart_item_id = NULL WHERE cart_item_id = :cart_item_id
            ");
            $update_deleted_item->execute(['cart_item_id' => $item['cart_item_id']]);

            // Increase the stock quantity by 1
            $update_stock = $conn->prepare("UPDATE books SET stock_quantity = stock_quantity + 1 WHERE book_id = :book_id");
            $update_stock->execute(['book_id' => $book_id]);
        }
    }

    // Redirect back to the previous page
    if (isset($_SERVER['HTTP_REFERER'])) {
        $refererUrl = $_SERVER['HTTP_REFERER'];
        $parsedUrl = parse_url($refererUrl, PHP_URL_PATH);

        if (basename($parsedUrl) == 'signinBooks.php') {
            $redirectUrl = "signinBooks.php?action=removed_from_cart&book_id=" . $book_id;
        } else if (basename($parsedUrl) == 'cart.php') {
            $redirectUrl = "cart.php?action=removed_from_cart&book_id=" . $book_id;
        } else {
            $redirectUrl = $_SERVER['HTTP_REFERER'] . "&action=removed_from_cart";
        }
    } else {
        $redirectUrl = "signinBooks.php?action=removed_from_cart&book_id=" . $book_id;
    }

    header("Location: " . $redirectUrl);
    exit();
} else {
    // Redirect to the cart page if no book_id is provided
    header("Location: cart.php");
    exit;
}
?>
