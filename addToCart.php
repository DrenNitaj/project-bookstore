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

    // Step 1: Check if the user already has a shopping cart
    $cart_query = $conn->prepare("SELECT cart_id FROM shopping_cart WHERE user_id = :user_id");
    $cart_query->execute(['user_id' => $user_id]);
    $cart = $cart_query->fetch();

    if (!$cart) {
        // If the shopping cart doesn't exist, create a new one
        $create_cart = $conn->prepare("INSERT INTO shopping_cart (user_id) VALUES (:user_id)");
        $create_cart->execute(['user_id' => $user_id]);
        $cart_id = $conn->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    // Step 2: Check if the book is already in the shopping cart
    $item_query = $conn->prepare("SELECT cart_item_id FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id");
    $item_query->execute(['cart_id' => $cart_id, 'book_id' => $book_id]);
    $item = $item_query->fetch();

    if (!$item) {
        // Fetch current stock quantity
        $stock_query = $conn->prepare("SELECT stock_quantity FROM books WHERE book_id = :book_id");
        $stock_query->execute(['book_id' => $book_id]);
        $stock = $stock_query->fetch();

        if ($stock && $stock['stock_quantity'] > 0) {
            // If stock is available, decrease by 1
            $new_stock = $stock['stock_quantity'] - 1;
            $update_stock = $conn->prepare("UPDATE books SET stock_quantity = :new_stock WHERE book_id = :book_id");
            $update_stock->execute(['new_stock' => $new_stock, 'book_id' => $book_id]);

            // Add the book to the shopping cart
            $add_item = $conn->prepare("INSERT INTO cart_items (cart_id, book_id, added_time) VALUES (:cart_id, :book_id, NOW())");
            $add_item->execute(['cart_id' => $cart_id, 'book_id' => $book_id]);
        }
    }

    // Redirect back to the previous page
    if (isset($_SERVER['HTTP_REFERER'])) {
        $refererUrl = $_SERVER['HTTP_REFERER'];
        $parsedUrl = parse_url($refererUrl, PHP_URL_PATH);

        if (basename($parsedUrl) == 'signinBooks.php') {
            $redirectUrl = "signinBooks.php?action=added_to_cart&book_id=" . $book_id;
        } else {
            $redirectUrl = $_SERVER['HTTP_REFERER'] . "&action=added_to_cart";
        }
    } else {
        $redirectUrl = "signinBooks.php?action=added_to_cart&book_id=" . $book_id;
    }

    header("Location: " . $redirectUrl);
    exit();
}
?>
