<?php

// Include the session check script
include('sessionCheck.php');

// connection with database
include_once("config.php");

if(empty($_SESSION['admin_id'])){
    header("Location: signinAdmin.php");
}

$book_id = $_GET['book_id'];

$sql = "DELETE FROM books WHERE book_id = :book_id";

$sqlPrep = $conn->prepare($sql);

$sqlPrep->bindParam(':book_id', $book_id);

$sqlPrep->execute();

header("Location: addBooks.php#books");


?>