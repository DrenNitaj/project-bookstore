<?php

// Include the session check script
include('sessionCheck.php');

// Connection with database
include_once("config.php");

// Check if a user or admin is logged in
if (empty($_SESSION['admin_id']) && empty($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Get review_id and book_id from the query string
$review_id = $_GET['review_id'];
$book_id = $_GET['book_id'];

// Get the logged-in user ID
$loggedInUserId = $_SESSION['user_id'] ?? $_SESSION['admin_id'];

// Check if review belongs to the logged-in user or if the user is an admin
$sql = $conn->prepare("
    SELECT user_id 
    FROM reviews 
    WHERE review_id = :review_id
");

$sql->bindParam(':review_id', $review_id, PDO::PARAM_INT);
$sql->execute();
$review = $sql->fetch(PDO::FETCH_ASSOC);

// If review does not belong to the user and the user is not an admin, deny deletion
if ($review && $review['user_id'] != $loggedInUserId && empty($_SESSION['admin_id'])) {
    // Redirect to a suitable page or show an error
    header("Location: signin.php"); // or a suitable error page
    exit();
}

// Proceed to delete the review
$sql = $conn->prepare("DELETE FROM reviews WHERE review_id = :review_id");
$sql->bindParam(':review_id', $review_id, PDO::PARAM_INT);
$sql->execute();

// Redirect back to the previous page
$redirectUrl = "signin.php"; // Default redirection URL

if (isset($_SERVER['HTTP_REFERER'])) {
    $refererUrl = $_SERVER['HTTP_REFERER'];
    $parsedUrl = parse_url($refererUrl, PHP_URL_PATH);

    switch (basename($parsedUrl)) {
        case 'signinBook.php':
            $redirectUrl = "signinBook.php?action=review-deleted&book_id=" . $book_id;
            break;
        case 'adminBookDetails.php':
            $redirectUrl = "adminBookDetails.php?action=review-deleted&book_id=" . $book_id;
            break;
        case 'account.php':
            $redirectUrl = "account.php?action=review-deleted&book_id=" . $book_id;
            break;
        default:
            $redirectUrl = "account.php?action=review-deleted&book_id=" . $book_id; // fallback to account
            break;
    }
}

header("Location: " . $redirectUrl);
exit();

?>
