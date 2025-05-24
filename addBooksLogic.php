<?php    
// Start the session
session_start();

// Include the session check script
include('sessionCheck.php');

// Include database connection
include_once("config.php");

// Redirect to sign-in page if not logged in as an admin
if (empty($_SESSION['admin_id'])) {
    header("Location: signinAdmin.php");
    exit;
}

if (isset($_POST['submit'])) {
    // Collect form data
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $author_name = $_POST['author_name'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $description = $_POST['description'];
    $cover_image_url = $_POST['cover_image_url'];

    // Check if the title already exists
    $sql = "SELECT title FROM books WHERE title = :title";
    $sqlCheckTitles = $conn->prepare($sql);
    $sqlCheckTitles->bindParam(':title', $title);
    $sqlCheckTitles->execute();

    if ($sqlCheckTitles->rowCount() > 0) {
        echo "Book is already added";
    } else {
        // Check if category_id exists in the categories table
        $categoryCheckSql = "SELECT category_id FROM categories WHERE category_id = :category_id";
        $categoryCheckStmt = $conn->prepare($categoryCheckSql);
        $categoryCheckStmt->bindParam(':category_id', $category_id);
        $categoryCheckStmt->execute();

        if ($categoryCheckStmt->rowCount() === 0) {
            echo "Error: Selected category does not exist.";
        } else {
            // Insert book into the database
            $sql = "INSERT INTO books(title, category_id, author_name, price, stock_quantity, description, cover_image_url)
                    VALUES (:title, :category_id, :author_name, :price, :stock_quantity, :description, :cover_image_url)";

            $sqlPrep = $conn->prepare($sql);
            $sqlPrep->bindParam(':title', $title);
            $sqlPrep->bindParam(':category_id', $category_id);
            $sqlPrep->bindParam(':author_name', $author_name);
            $sqlPrep->bindParam(':price', $price);
            $sqlPrep->bindParam(':stock_quantity', $stock_quantity);
            $sqlPrep->bindParam(':description', $description);
            $sqlPrep->bindParam(':cover_image_url', $cover_image_url);
            
            $sqlPrep->execute();

            // Get the last inserted book ID
            $book_id = $conn->lastInsertId();

            // Redirect with success action
            header("Location: addBooks.php?action=book-added&book_id=" . $book_id);
            exit;
        }
    }
}
?>
