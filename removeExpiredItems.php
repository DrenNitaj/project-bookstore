<?php

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

if(empty($_SESSION['user_id'])){
    header("Location: signin.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the cart ID
    $cart_query = $conn->prepare("SELECT cart_id FROM shopping_cart WHERE user_id = :user_id");
    $cart_query->execute(['user_id' => $user_id]);
    $cart = $cart_query->fetch();

    if ($cart) {
        $cart_id = $cart['cart_id'];

        // Fetch expired items
        $expired_items_query = $conn->prepare("
            SELECT cart_item_id, book_id 
            FROM cart_items 
            WHERE cart_id = :cart_id AND TIMESTAMPDIFF(MINUTE, added_time, NOW()) >= 60
        ");
        $expired_items_query->execute(['cart_id' => $cart_id]);
        $expired_items = $expired_items_query->fetchAll();

        if ($expired_items) {
            foreach ($expired_items as $expired_item) {
                // Restore the stock quantity
                $update_stock = $conn->prepare("UPDATE books SET stock_quantity = stock_quantity + 1 WHERE book_id = :book_id");
                $update_stock->execute(['book_id' => $expired_item['book_id']]);

                // Remove expired item from the cart
                $delete_item = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = :cart_item_id");
                $delete_item->execute(['cart_item_id' => $expired_item['cart_item_id']]);
            }
        }
    }
}
?>
