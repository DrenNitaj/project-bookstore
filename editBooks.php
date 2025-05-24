<?php
// Include the session check script
include('sessionCheck.php');

// connection with database
include_once("config.php");

if(empty($_SESSION['admin_id'])){
    header("Location: signinAdmin.php");
}


$book_id = $_GET['book_id'];

$sql = "
    SELECT b.*, c.name AS category_name
    FROM books b
    JOIN categories c ON b.category_id = c.category_id
    WHERE b.book_id = :book_id
";

$sqlPrep = $conn->prepare($sql);
$sqlPrep->bindParam(':book_id', $book_id);
$sqlPrep->execute();

$book = $sqlPrep->fetch();


// var_dump($data);



$sql = "SELECT * FROM categories";

$sqlPrep = $conn->prepare($sql);

$sqlPrep->execute();

$categories = $sqlPrep->fetchAll();


if(empty($_SESSION['admin_id'])){
    header("Location: signinAdmin.php");
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" type="text/css" href="css/admin.css?v=1.1">
    <title>the BookHouse / Add Books</title>
    <link rel="icon" href="images/logo.jpg">
</head>
<body>


    <header>
		<div class="logo">
            <img src="images/logo1.png" alt="Logo">
            <h1>the BookHouse</h1>
        </div>
		<ul>
			<li class="active"><a href="addBooks.php">BOOKS</a></li>
            <li><a href="cart.php">CARTS</a></li>
            <li><a href="wishlist.php">WISHLISTS</a></li>
            <li><a href="purchases.php">PURCHASES</a></li>
            <!-- <li><a href="user.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>  ACCOUNT</a></li> -->
            <li><a action="logoutAdmin.php" href="logoutAdmin.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>


    <div style="margin-top: 98.188px;" class="form-container">
        
        <h1 class="heading">Edit Book</h1>
        <form action="updateBooks.php" method="post" enctype="multipart/form-data">

            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">

            <div class="input-div">
                <input type="text" class="input disabled" placeholder="Title" name="title" value="<?php echo $book['title'];?>" required>
                <label>Title</label>
            </div>
            <div class="input-div">
                <select class="input select" name="category_id" required>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $book['category_id']) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php } ?>
                </select>
                <label>Category</label>
            </div>
            <div class="input-div">
                <input class="input disabled" placeholder="Author" name="author_name" value="<?php echo $book['author_name'];?>" required>
                <label>Author</label>
            </div>
            <div class="input-div">
                <input type="number" class="input" placeholder="Price (in Euros)" name="price" step="any" value="<?php echo $book['price'];?>" required>
                <label>Price</label>
            </div>
            <div class="input-div">
                <input type="number" class="input" placeholder="Stock quantity" name="stock_quantity" value="<?php echo $book['stock_quantity'];?>" required>
                <label>Stock quantity</label>
            </div>
            <div class="input-div">
                <textarea type="text" class="input" placeholder="Description" name="description"  value=""><?php echo $book['description'];?></textarea>
                <label>Description</label>
            </div>
            <div class="input-div">
                <input type="text" class="input" placeholder="Cover Image URL" name="cover_image_url" value="images/coverimages/<?php echo $book['cover_image_url'];?>" readonly>
                <label>Cover Image URL</label>
                <!-- Hidden field to store the current URL -->
                <input type="hidden" name="current_cover_image_url" value="<?php echo $book['cover_image_url']; ?>">
            </div>
            <div class="input-div">
                <input type="file" class="input" name="new_cover_image_url">
                <label>Upload New Cover Image (optional)</label>
            </div>
            <button class="submit" type="submit" name="submit">Submit</button>
        </form>
    </div>






    <script>
        let timeoutDuration = 1800000; // 30 minutes in milliseconds
        let timeout;

        function resetTimeout() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                // No action here, just wait for the next user interaction to reset the timer
            }, timeoutDuration);
        }

        // Detect user activities
        window.onload = resetTimeout; // Reset timeout on page load
        document.onmousemove = resetTimeout; // Reset timeout on mouse movement
        document.onkeypress = resetTimeout; // Reset timeout on key press
        document.onclick = resetTimeout; // Reset timeout on click
    </script>
    <script type="text/javascript" src="js/main.js?v=1.1"></script>
</body>
</html>