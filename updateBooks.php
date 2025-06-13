<?php
    // Start the session
    session_start();

    // Include the session check script
    include('sessionCheck.php');

    // Include database connection
    include_once("config.php");

    if(empty($_SESSION['admin_id'])){
        header("Location: signinAdmin.php");
    }

    if (isset($_POST['submit'])){


        $book_id = $_POST['book_id'];
        $title = $_POST['title'];
        $category_id = $_POST['category_id'];
        $author_name = $_POST['author_name'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $description = $_POST['description'];
        $cover_image_url = $_POST['cover_image_url'];
        $current_cover_image_url = $_POST['current_cover_image_url'];

        // Handle file upload
        if (isset($_FILES['new_cover_image_url']) && $_FILES['new_cover_image_url']['error'] == UPLOAD_ERR_OK) {
            $uploaded_file_name = $_FILES['new_cover_image_url']['name'];
            $target_dir = "images/coverimages/";
            $target_file = $target_dir . basename($uploaded_file_name);
            move_uploaded_file($_FILES['new_cover_image_url']['tmp_name'], $target_file);
            $cover_image_url = $uploaded_file_name; // Update with the new file name
        } else {
            $cover_image_url = $current_cover_image_url; // Keep the existing URL if no new file is uploaded
        }


        $sql = "SELECT title FROM books WHERE title=:title  AND book_id != :book_id";
        $sqlCheckTitles = $conn->prepare($sql);
        $sqlCheckTitles->bindParam(':title', $title);
        $sqlCheckTitles->bindParam(':book_id', $book_id);
        $sqlCheckTitles->execute();

        $sql = "SELECT author_name FROM books WHERE author_name=:author_name AND book_id != :book_id";
        $sqlCheckAuthors = $conn->prepare($sql);
        $sqlCheckAuthors->bindParam(':author_name', $author_name);
        $sqlCheckAuthors->bindParam(':book_id', $book_id);
        $sqlCheckAuthors->execute();

        if ($sqlCheckTitles->rowCount() > 0 & $sqlCheckAuthors->rowCount() > 0){
            echo "Book is already added";
        } else {

        $sql = "UPDATE books SET title=:title, category_id=:category_id, author_name=:author_name, price=:price, stock_quantity=:stock_quantity, description=:description, cover_image_url=:cover_image_url WHERE book_id=:book_id";
        // $sql = "UPDATE books SET price=:price, stock_quantity=:stock_quantity, description=:description, cover_image_url=:cover_image_url WHERE book_id=:book_id";

        $sqlPrep = $conn->prepare($sql);

        $sqlPrep->bindParam(':book_id', $book_id);
        $sqlPrep->bindParam(':title', $title);
        $sqlPrep->bindParam(':category_id', $category_id);
        $sqlPrep->bindParam(':author_name', $author_name);
        $sqlPrep->bindParam(':price', $price);
        $sqlPrep->bindParam(':stock_quantity', $stock_quantity);
        $sqlPrep->bindParam(':description', $description);
        $sqlPrep->bindParam(':cover_image_url', $cover_image_url);
        

        $sqlPrep->execute();

        header("Location: addBooks.php?action=book-updated#books");

        }

    }