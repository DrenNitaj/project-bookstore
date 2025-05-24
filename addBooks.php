<?php

    // Start the session
    session_start();

    // Include the session check script
    include('sessionCheck.php');

    // connect with database
    include_once("config.php");

    if(empty($_SESSION['admin_id'])){
        header("Location: signinAdmin.php");
    }



    $sql = "SELECT * FROM categories";

    $sqlPrep = $conn->prepare($sql);

    $sqlPrep->execute();

    $categories = $sqlPrep->fetchAll();



    $sql = "
        SELECT b.book_id, b.title, b.author_name, b.price, b.stock_quantity, b.description, b.cover_image_url, c.name AS category_name
        FROM books b
        JOIN categories c ON b.category_id = c.category_id
        ORDER BY b.book_id ASC
    ";

    $sqlPrep = $conn->prepare($sql);
    
    $sqlPrep->execute();

    $books = $sqlPrep->fetchAll();




    $sql = "
    SELECT * 
    FROM books
    ORDER BY book_id DESC
    LIMIT 1
    ";

    $sqlPrep = $conn->prepare($sql);

    $sqlPrep->execute();

    $lastBook = $sqlPrep->fetch();


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
            <li><a href="purchases.php">PURCHASES</a></li>
            <li><a href="users.php">USERS</a></li>
            <!-- <li><a href="user.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>  ACCOUNT</a></li> -->
            <li><a action="logoutAdmin.php" href="logoutAdmin.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>
    

    <div class="custom-alert" id="welcomeAlert">
        <h2 class="welcome-text">Welcome <?php echo $_SESSION['admin_name'] ?>!</h2>
        <p>Manage your bookstore efficiently and add new titles to our collection.</p>
    </div>
    

    <div class="form-container">
        
        <h1 class="heading">Add Books</h1>
        <form action="addBooksLogic.php" method="post">

            <div class="input-div">
                <input type="text" class="input" placeholder="Title" name="title" required>
                <label>Title</label>
            </div>
            <div class="input-div">
                <select class="input select" name="category_id" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
                <label>Category</label>
            </div>
            <div class="input-div">
                <input class="input" placeholder="Author" name="author_name" required>
                <label>Author</label>
            </div>
            <div class="input-div">
                <input type="number" class="input" placeholder="Price (in Euros)" name="price" step="any" required>
                <label>Price</label>
            </div>
            <div class="input-div">
                <input type="number" class="input" placeholder="Stock quantity" name="stock_quantity" required>
                <label>Stock quantity</label>
            </div>
            <div class="input-div">
                <textarea type="text" class="input" placeholder="Description" name="description"></textarea>
                <label>Description</label>
            </div>
            <div class="input-div">
                <input style="background-color: white;" type="file" class="input" placeholder="Cover Image" name="cover_image_url">
                <label>Cover Image URL</label>
            </div>
            <button class="submit" type="submit" name="submit">Add Book</button>
        </form>
    </div>

    <div class="table" id="books">
        <div class="heading-searchBox">
            <h1 class="heading">Books</h1>
            <form class="search-form" onsubmit="event.preventDefault(); search();">
                <input type="text" class="searchBox" placeholder="Search for Books..." onkeyup="search()">
                <button class="submit" type="submit">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </form>
        </div>
        <table id="table">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <!-- <th>Description</th> -->
                    <th>Cover Image</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book){ ?>
                    <tr class="clickable-row" data-href="adminBookDetails.php?book_id=<?php echo $book['book_id']; ?>">
                        <td data-label="Book ID"><?php echo $book['book_id']; ?></td>
                        <td data-label="Title" class="title"><?php echo $book['title']; ?></td>
                        <td data-label="Category"><?php echo $book['category_name']; ?></td>
                        <td data-label="Author"><?php echo $book['author_name']; ?></td>
                        <td data-label="Price" class="price"><?php echo $book['price']; ?> €</td>
                        <td data-label="Stock Quantity"><?php echo $book['stock_quantity']; ?></td>
                        <!-- <td data-label="Description" class="description"><?php echo $book['description']; ?></td> -->
                        <td data-label="Cover Image"><img src="images/coverimages/<?php echo $book['cover_image_url']; ?>" alt="Cover Image" class="cover-image"></td>
                        <td data-label="Delete">
                            <a href="#" class="delete-link" data-book-id="<?php echo $book['book_id']; ?>">Delete</a>
                        </td>
                        <td data-label="Edit">
                            <a href="editBooks.php?book_id=<?php echo $book['book_id']; ?>" class="edit-link">Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h1 id="no-results" style="display: none;">No results found</h1>
    </div>


    <div class="overlay">
        <div class="dialog">
            <p>Are you sure?</p>
            <div class="buttons">
                <a class="no">No</a>
                <a class="yes">Yes</a>
            </div>
        </div>
    </div>



    <div class="alert" id="book-alert">
        <img src="images/coverimages/<?php echo $lastBook['cover_image_url'];?>" alt="<?php echo $lastBook['title']; ?>">
        <div>
            <h1 title="<?php echo $lastBook['title'];?>"><?php echo $lastBook['title'];?></h1>                            
            <h1>Book added successfully</h1>
        </div>
    </div>






    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.querySelector('.overlay');
            const deleteLinks = document.querySelectorAll('.delete-link');
            const noLink = document.querySelector('.no');
            const yesLink = document.querySelector('.yes');

            let currentBookId = null;

            deleteLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation(); // Prevent the click from bubbling up
                    currentBookId = this.getAttribute('data-book-id');
                    overlay.classList.add('showww');
                });
            });

            noLink.addEventListener('click', function () {
                overlay.classList.remove('showww');
            });

            yesLink.addEventListener('click', function () {
                if (currentBookId) {
                    window.location.href = `deleteBooks.php?book_id=${currentBookId}`;
                }
            });

            // Make the entire row clickable
            document.querySelectorAll('.clickable-row').forEach(function(row) {
                row.addEventListener('click', function() {
                    window.location.href = this.dataset.href;
                });
            });
        });





        document.addEventListener('DOMContentLoaded', () => {
            const welcomeText = document.querySelector('.welcome-text');

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('showwww');
                    }
                });
            }, { threshold: 0.20 });

            observer.observe(welcomeText);
        });







        let debounceTimeout;

        function search() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                const query = document.querySelector('.searchBox').value.toLowerCase().trim();
                const books = document.querySelectorAll('tbody tr');
                let resultsFound = false;

                books.forEach(function(book) {
                    const title = book.querySelector('.title').textContent.toLowerCase();
                    if (query === '' || title.includes(query)) {
                        book.style.display = ''; // Show the row by using the default display property
                        resultsFound = true;
                    } else {
                        book.style.display = 'none'; // Hide the row
                    }
                });

                // Show or hide the "No results found" message
                const noResultsMessage = document.getElementById('no-results');
                if (resultsFound) {
                    noResultsMessage.style.display = 'none';
                } else {
                    noResultsMessage.style.display = 'block';
                }

            }, 300); // Adjust debounce delay as needed
        }






        document.addEventListener('DOMContentLoaded', function() {
            // Handle the URL parameters for the alert
            const url = new URL(window.location);
            const action = url.searchParams.get('action');
            const bookId = url.searchParams.get('book_id');

            if (action === 'book-added' && bookId) {
                showBookAlert();
                setTimeout(hideBookAlert, 3000); // Hide after 3 seconds
                removeUrlParams(); // Remove parameters from URL
            }

            function showBookAlert() {
                const bookAlert = document.getElementById('book-alert');
                if (bookAlert) {
                    bookAlert.style.display = 'flex'; // Show the alert
                }
            }

            function hideBookAlert() {
                const bookAlert = document.getElementById('book-alert');
                if (bookAlert) {
                    bookAlert.style.display = 'none'; // Hide the alert
                }
            }

            function removeUrlParams() {
                const url = new URL(window.location);
                url.searchParams.delete('action');
                url.searchParams.delete('book_id');
                window.history.replaceState({}, document.title, url);
            }
        });






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


<?php 
    // Debug: Check if the session variable is set
    if (isset($_SESSION['show_welcome_alert'])) {

        // Output the JavaScript code to show the alert
        echo "<script>
                function showAlert() {
                    const alertBox = document.getElementById('welcomeAlert');
                    alertBox.style.display = 'flex'; // Display the alert box

                    // Hide the alert after 8 seconds
                    setTimeout(() => {
                        alertBox.style.display = 'none';
                    }, 4000);
                }

                window.onload = showAlert;
            </script>";

            // Unset the session variable to prevent alert from showing again
            unset($_SESSION['show_welcome_alert']);

        // echo "Welcome alert session variable is set."; // Debugging output
    } else {
        // echo "Welcome alert session variable is not set."; // Debugging output
    }
?>