<?php

include_once("config.php");

$sql = "SELECT COUNT(*) FROM books";
$sqlCountBooks = $conn->prepare($sql);
$sqlCountBooks->execute();
$sqlBooksNumber = $sqlCountBooks->fetchColumn(); // Get the count result

$sql = "SELECT COUNT(*) FROM users";
$sqlCountUsers = $conn->prepare($sql);
$sqlCountUsers->execute();
$sqlUsersNumber = $sqlCountUsers->fetchColumn(); // Get the count result

$sql = "SELECT * 
        FROM books 
        ORDER BY book_id DESC 
        LIMIT 3";

$sqlPrep = $conn->prepare($sql);

$sqlPrep->execute();

$newBooks = $sqlPrep->fetchAll();



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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=1.1">
    <title>the BookHouse</title>
    <link rel="icon" href="images/logo.jpg">
</head>
<body>
    
    <header>    
		<div class="logo">
            <img src="images/logo1.png" alt="Logo">
            <h1>the BookHouse</h1>
        </div>
		<ul class="ulIndex">
			<li class="active"><a href="index.php">HOME</a></li>
            <li><a href="books.php" target="_blank">BOOKS</a></li>
            <li><a href="signin.php" target="_blank">SIGN IN</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>

    <section id="section1">
        <section class="books-contain activee" id="books1">
            <div class="books-container">
                <div class="books-text">
                    <h1>Discover a New Literary World</h1>
                    <p>Dive into our latest collection of bestsellers, carefully curated to inspire and delight.</p>
                </div>
                <div class="buttons">
                    <a href="books.php" target="_blank"><button>Explore Collection</button></a>
                    <a href="signin.php" target="_blank"><button>Shop Now</button></a>
                </div>
            </div>
        </section>

        <section class="books-contain" id="books2">
            <div class="books-container">
                <div class="books-text">
                    <h1>Your Personalized Reading Experience</h1>
                    <p>Find books that resonate with your taste using our personalized recommendations.</p>
                </div>
                <div class="buttons">
                    <a href="signin.php" class="learnMoreLink" target="_blank"><button>Get Recommendations</button></a>
                    <a href="books.php" target="_blank"><button>Browse All</button></a>
                </div>
            </div>
        </section>

        <section class="books-contain" id="books3">
            <div class="books-container">
                <div class="books-text">
                    <h1>Book Club Picks</h1>
                    <p>Join our book club and explore thought-provoking reads chosen by literary experts.</p>
                </div>
                <div class="buttons">
                    <a href="signin.php" target="_blank"><button>Join Now</button></a>
                </div>
            </div>
        </section>

        <section class="books-contain" id="books4">
            <div class="books-container">
                <div class="books-text">
                    <h1>New Arrivals Just In</h1>
                    <p>Be the first to read our newest additions, fresh off the press.</p>
                </div>
                <div class="buttons">
                    <a class="newBooksButton" href="books.php" target="_blank"><button>Browse New Arrivals</button></a>
                    <a href="signin.php" target="_blank"><button>Pre-Order</button></a>
                </div>
            </div>
        </section>
    </section>



    <section id="section2">
        <h1>Welcome to The BookHouse</h1>
        <p>Your one-stop destination for the best books, events, and community.</p>
        <div id="highlights">
            <div class="highlight-item">
                <div class="card-inner">
                    <div class="card-front">
                        <h2>Diverse Collection</h2>
                        <p>We offer a vast selection of books across all genres to suit every reader.</p>
                    </div>
                    <div class="card-back">
                        <div class="bg-image" style="background-image: url('images/genres.jpg');"></div>
                        <h2>Explore Our Genres</h2>
                    </div>
                </div>
            </div>

            <div class="highlight-item">
                <div class="card-inner">
                    <div class="card-front">
                        <h2>Community Events</h2>
                        <p>Join us for book readings, author signings, and discussion groups.</p>
                    </div>
                    <div class="card-back">
                        <div class="bg-image" style="background-image: url('images/events.jpg');"></div>
                        <h2>Join Our Events</h2>
                    </div>
                </div>
            </div>

            <div class="highlight-item">
                <div class="card-inner">
                    <div class="card-front">
                        <h2>Personalized Recommendations</h2>
                        <p>Get tailored book suggestions from our experienced staff.</p>
                    </div>
                    <div class="card-back">
                        <div class="bg-image" style="background-image: url('images/recommendations.jpg');"></div>
                        <h2>Get Custom Picks</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section3">
        <div id="about-us">
            <h1>About The BookHouse</h1>
            <p>At The BookHouse, our mission is to create a welcoming environment for book lovers. We believe in fostering a love of reading by providing a cozy space, a wide range of books, and engaging events for our community.</p>
            <a href="" class="learn-more-btn" id="learnMoreBtn">Learn More About Us</a>
        </div>
    </section>

    <div class="overlay" id="aboutOverlay">
        <div class="overlay-content">
            <span class="close-btn" id="closeOverlay">&times;</span>
            <h2>Our Story</h2>
            <div class="overlay-image"></div>
            <p>Founded in 2010, The BookHouse began as a small neighborhood bookstore with just 500 titles. Today, we're proud to house over 50,000 books across all genres, serving book lovers from all walks of life.</p>
            
            <h3>Our Philosophy</h3>
            <p>We believe books have the power to transform lives. Our carefully curated collection is designed to inspire, educate, and entertain readers of all ages. Our knowledgeable staff are always ready to help you find your next great read.</p>
            
            <h3>The BookHouse Experience</h3>
            <p>More than just a bookstore, we're a community hub. Our space features:</p>
                <p class="list-p">• Comfortable reading nooks with armchairs and natural light</p>
                <p class="list-p">• A children's corner with weekly storytime sessions</p>
                <p class="list-p">• An in-house café serving artisanal coffee and pastries</p>
                <p class="list-p">• Monthly author events and book club meetings</p>
                <p class="list-p">• Writing workshops and literary discussions</p>
            
            <h3>Our Team</h3>
            <p>Our staff consists of passionate bibliophiles with diverse reading interests. From Pulitzer Prize winners to the latest fantasy series, we've got you covered with personalized recommendations.</p>
        </div>
    </div>

    <section id="section4">
            <div class="section4-content">
                <div class="section4-title">
                    <h1>Discover the Newest Books</h1>
                    <a href="books.php" target="_blank" id="newBooksButton" class="newBooksButton"><button>See What's New</button></a>
                </div>
                <div class="books-wrapper">
                    <?php foreach($newBooks as $index => $newBook): ?>
                        <div data-book-id="<?php echo $newBook['book_id'] ?>" class="book 
                            <?php 
                                if ($index === 0) echo 'main-book'; 
                                elseif ($index === 1) echo 'rotated-book-left'; 
                                elseif ($index === 2) echo 'rotated-book-right'; 
                            ?>">
                            <img src="images/coverimages/<?php echo $newBook['cover_image_url'];?>" alt="Book <?php echo $index + 1; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
    </section>

    <section id="section5">
        <div class="stats">
            <div class="stat-item">
                <h1 data-target="<?php echo $sqlBooksNumber; ?>">0</h1>
                <p>Books in Stock</p>
            </div>
            <div class="stat-item">
                <h1 data-target="15">0</h1>
                <p>Years in Business</p>
            </div>
            <div class="stat-item">
                <h1 data-target="<?php echo $sqlUsersNumber; ?>">0</h1>
                <p>Satisfied Customers</p>
            </div>
        </div>
        <div id="section5-div">
            <h1>Our Journey in Numbers</h1>
            <p>From a small local bookstore to a beloved destination for book lovers, we've grown thanks to our passion for books and our loyal customers. These numbers are a testament to our journey.</p>
        </div>
    </section>


    <!-- <section id="section6">
        <h1>What our readers say</h1>
        <div class="slider">
            <div class="slide active">
                <h3>"I love the cozy environment here. It feels like a second home where I can truly relax and enjoy my reads."</h3>
                <p>– User</p>
            </div>
            <div class="slide">
                <h3>"This bookstore has an amazing collection. I always find what I'm looking for."</h3>
                <p>– User</p>
            </div>
            <div class="slide">
                <h3>"The staff is incredibly helpful and knowledgeable. I get great recommendations every time I visit."</h3>
                <p>– User</p>
            </div>
            <div class="slide">
                <h3>"There's something special about this place. It's my go-to spot for all things books."</h3>
                <p>– User</p>
            </div>
            <div class="navigation-manual">
                <label for="radio1" class="manual-btn"></label>
                <label for="radio2" class="manual-btn"></label>
                <label for="radio3" class="manual-btn"></label>
                <label for="radio4" class="manual-btn"></label>
            </div>
        </div>
    </section> -->

    <section id="section7">
        <h1>Stay Connected</h1>
        <p>Follow us on social media for the latest updates, news, and events.</p>
        <div class="social-links">
            <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
            <a href="https://x.com/"><i class="fa-brands fa-x-twitter"></i></i></a>
            <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
            <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
            <a href="https://www.tiktok.com/"><i class="fab fa-tiktok"></i></a>
        </div>
    </section>


    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-pages-legal">
                <!-- Pages Menu -->
                <div class="footer-menu footer-pages">
                    <h3>Pages</h3>
                    <ul class="footer-nav">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="books.php" target="_blank">Books</a></li>
                        <li><a href="signin.php" target="_blank">Sign In</a></li>
                    </ul>
                </div>

                <!-- Legal Menu -->
                <div class="footer-menu footer-legal">
                    <h3>Legal</h3>
                    <ul class="footer-nav">
                        <li><a href="#">Legal Terms</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookies</a></li>
                        <li><a href="#">Accessibility</a></li>
                    </ul>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="footer-social">
                <h3>Follow Us</h3>
                <ul class="footer-nav">
                    <li><a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="https://x.com/"><i class="fa-brands fa-x-twitter"></i></a></li>
                    <li><a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a></li>
                    <li><a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a></li>
                    <li><a href="https://www.tiktok.com/"><i class="fab fa-tiktok"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>

    
    <div class="nav-overlay"></div>
     

    <script type="text/javascript" src="js/main.js?v=1.1"></script>
</body>
</html>