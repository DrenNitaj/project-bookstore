<?php
session_start();
include('sessionCheck.php');
include_once("config.php");

if (empty($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

function encryptSimulated($value) {
    return base64_encode($value);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = (int)$_POST['book_id'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $delivery_method = $_POST['delivery_method'];
    $shipping_address = htmlspecialchars(trim($_POST['shipping_address']), ENT_QUOTES);
    $cardholder_name = htmlspecialchars(trim($_POST['cardholder_name']), ENT_QUOTES);
    $card_number = trim($_POST['card_number']);
    $raw_expiry = trim($_POST['expiry_date']);
    $cvv = trim($_POST['cvv']);

    // Encrypt sensitive data early
    $enc_card_number = encryptSimulated($card_number);
    $enc_expiry_date = encryptSimulated(date('m/y', strtotime($raw_expiry)) ?: '');
    $enc_cvv = encryptSimulated($cvv);

    // Insert initial failed purchase
    $purchase_stmt = $conn->prepare("
        INSERT INTO purchases
            (user_id, total_amount, delivery_method, shipping_address, cardholder_name,
             encrypted_card_number, encrypted_expiry_date, encrypted_cvv, status)
        VALUES (?, 0, ?, ?, ?, ?, ?, ?, 'failed')
    ");
    $purchase_stmt->execute([
        $user_id, $delivery_method, $shipping_address,
        $cardholder_name, $enc_card_number, $enc_expiry_date, $enc_cvv
    ]);
    $purchase_id = $conn->lastInsertId();

    $book_stmt = $conn->prepare("SELECT price, stock_quantity FROM books WHERE book_id = ?");
    $book_stmt->execute([$book_id]);
    $book = $book_stmt->fetch();

    // Add purchase item regardless of outcome
    if ($book) {
        $item_stmt = $conn->prepare("
            INSERT INTO purchase_items (purchase_id, book_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $item_stmt->execute([$purchase_id, $book_id, $quantity, $price]);
    }

    // Validation
    if (
        $quantity >= 1 && $quantity <= 5 &&
        in_array($delivery_method, ['standard', 'express']) &&
        !empty($shipping_address) && !empty($cardholder_name) &&
        !empty($card_number) && !empty($raw_expiry) && !empty($cvv) &&
        preg_match('/^\d{13,19}$/', $card_number) &&
        preg_match('/^\d{3,4}$/', $cvv)
    ) {
        $expiry_timestamp = strtotime($raw_expiry);
        if ($expiry_timestamp && $expiry_timestamp >= strtotime(date('Y-m-01'))) {
            if ($book && abs($price - $book['price']) <= 0.01) {
                if ($book['stock_quantity'] < $quantity) {
                    $_SESSION['purchase_failed'] = true;
                    header("Location: failedPurchase.php");
                    exit();
                }

                // All valid — process transaction
                $delivery_fee = ($delivery_method === 'express') ? 4.99 : 0;
                $total_amount = ($price * $quantity) + $delivery_fee;

                $conn->beginTransaction();
                try {
                    $new_stock = $book['stock_quantity'] - $quantity;
                    $stock_stmt = $conn->prepare("UPDATE books SET stock_quantity = ? WHERE book_id = ?");
                    $stock_stmt->execute([$new_stock, $book_id]);

                    $update_stmt = $conn->prepare("
                        UPDATE purchases SET total_amount = ?, status = 'processed'
                        WHERE purchase_id = ?
                    ");
                    $update_stmt->execute([$total_amount, $purchase_id]);

                    $conn->commit();
                    $_SESSION['purchase_confirmed'] = true;
                    header("Location: confirmedPurchase.php");
                    exit();
                } catch (Exception $e) {
                    $conn->rollBack();
                    $_SESSION['purchase_failed'] = true;
                    header("Location: failedPurchase.php");
                    exit();
                }
            }
        }
    }

    // Default failure path
    $_SESSION['purchase_failed'] = true;
    header("Location: failedPurchase.php");
    exit();
} else {
    header("Location: signinBooks.php");
    exit();
}
