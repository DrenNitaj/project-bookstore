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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Capture the review from the form input
    $review = $_POST['review'];
    $book_id = $_GET['book_id'];  // Assuming book_id is passed via GET
    $user_id = $_SESSION['user_id'];  // Assuming user_id is stored in session

    // Check if review is not empty
    if (!empty($review)) {
        // Prepare SQL statement to insert the review
        $sql = "INSERT INTO reviews (book_id, user_id, comment) VALUES (:book_id, :user_id, :comment)";
        $sql = $conn->prepare($sql);

        // Bind parameters
        $sql->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sql->bindParam(':comment', $review, PDO::PARAM_STR);

        // Execute the statement and check if it was successful
        if ($sql->execute()) {
            $redirectUrl = "signinBook.php?action=review-added&book_id=" . $book_id . "&user_id=" . $user_id;
            header("Location: " . $redirectUrl);
            exit;
        } else {
            echo "Error adding review.";
        }
    } else {
        echo "Review cannot be empty.";
    }
} else {
    echo "Invalid request.";
}

?>
