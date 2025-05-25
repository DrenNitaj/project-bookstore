<?php
include('sessionCheck.php');
include_once("config.php");
include_once("removeExpiredItems.php");

if (empty($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Step 1: Collect interacted book IDs
$interactedBookIds = [];
$queries = [
    "SELECT book_id FROM wishlist_items wi JOIN wishlists w ON wi.wishlist_id = w.wishlist_id WHERE w.user_id = ?",
    "SELECT book_id FROM cart_items ci JOIN shopping_cart c ON ci.cart_id = c.cart_id WHERE c.user_id = ?",
    "SELECT book_id FROM reviews WHERE user_id = ?"
];

foreach ($queries as $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $interactedBookIds[] = $row['book_id'];
    }
}

$interactedBookIds = array_unique($interactedBookIds);
$recommendedBookIds = [];

$recommendations = [
    'author' => [],
    'category' => [],
    'price' => [],
    'fallback' => []
];

if (!empty($interactedBookIds)) {
    $placeholders = implode(',', array_fill(0, count($interactedBookIds), '?'));
    $metaStmt = $conn->prepare("SELECT author_name, category_id, price FROM books WHERE book_id IN ($placeholders)");
    $metaStmt->execute($interactedBookIds);

    $authors = [];
    $categories = [];
    $prices = [];

    while ($row = $metaStmt->fetch(PDO::FETCH_ASSOC)) {
        $authors[] = $row['author_name'];
        $categories[] = $row['category_id'];
        $prices[] = $row['price'];
    }

    $authors = array_unique($authors);
    $categories = array_unique($categories);
    $prices = array_unique($prices);

    // Author Recommendations
    if ($authors) {
        $placeholders = implode(',', array_fill(0, count($authors), '?'));
        $sql = "SELECT b.*, c.name AS category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.category_id 
                WHERE b.author_name IN ($placeholders)";
        if ($interactedBookIds) {
            $sql .= " AND b.book_id NOT IN (" . implode(',', array_fill(0, count($interactedBookIds), '?')) . ")";
        }
        $sql .= " LIMIT 6";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($authors, $interactedBookIds));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!in_array($row['book_id'], $recommendedBookIds)) {
                $recommendations['author'][] = $row;
                $recommendedBookIds[] = $row['book_id'];
                if (count($recommendations['author']) >= 3) break;
            }
        }
    }

    // Category Recommendations
    if ($categories) {
        $placeholders = implode(',', array_fill(0, count($categories), '?'));
        $sql = "SELECT b.*, c.name AS category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.category_id 
                WHERE b.category_id IN ($placeholders)";
        if ($interactedBookIds) {
            $sql .= " AND b.book_id NOT IN (" . implode(',', array_fill(0, count($interactedBookIds), '?')) . ")";
        }
        $sql .= " LIMIT 6";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($categories, $interactedBookIds));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!in_array($row['book_id'], $recommendedBookIds)) {
                $recommendations['category'][] = $row;
                $recommendedBookIds[] = $row['book_id'];
                if (count($recommendations['category']) >= 3) break;
            }
        }
    }

    // Price Recommendations (±10%)
    if ($prices) {
        $min = min($prices) * 0.9;
        $max = max($prices) * 1.1;
        $sql = "SELECT b.*, c.name AS category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.category_id 
                WHERE b.price BETWEEN ? AND ?";
        if ($interactedBookIds) {
            $sql .= " AND b.book_id NOT IN (" . implode(',', array_fill(0, count($interactedBookIds), '?')) . ")";
        }
        $sql .= " ORDER BY RAND() LIMIT 6";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge([$min, $max], $interactedBookIds));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!in_array($row['book_id'], $recommendedBookIds)) {
                $recommendations['price'][] = $row;
                $recommendedBookIds[] = $row['book_id'];
                if (count($recommendations['price']) >= 3) break;
            }
        }
    }
}

// Fallback: if no data from user
if (empty($recommendations['author']) && empty($recommendations['category']) && empty($recommendations['price'])) {
    $stmt = $conn->query("
        SELECT b.*, c.name AS category_name 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.category_id 
        ORDER BY RAND() LIMIT 3
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $recommendations['fallback'][] = $row;
    }
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
    <link rel="stylesheet" type="text/css" href="css/user.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>the BookHouse / Recommendations</title>
    <link rel="icon" href="images/logo.jpg">
</head>
<body>
    
    <header>
		<div class="logo">
            <img src="images/logo1.png" alt="Logo">
            <h1>the BookHouse</h1>
        </div>
		<ul class="ulSignin">
			<li><a href="signinBooks.php">BOOKS</a></li>
            <li><a href="cart.php">CART</a></li>
            <li><a href="wishlist.php">WISHLIST</a></li>
            <li><a href="purchases.php">PURCHASES</a></li>
            <li><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
            <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>

    
<div style="display: none;" class="recommendation-page">
    <?php foreach (['author' => 'Same Author', 'category' => 'Same Category', 'price' => 'Similar Price', 'fallback' => 'Random Picks'] as $key => $label): ?>
        <?php if (!empty($recommendations[$key])): ?>
        <section class="recommendation-section">
            <h1><?= $label ?> Recommendations</h1>
            <div class="books">
                <?php foreach ($recommendations[$key] as $book): ?>
                    <div class="book-card">
                        <a href="book.php?book_id=<?= htmlspecialchars($book['book_id']) ?>" class="book-card-link">
                            <img src="images/coverimages/<?= htmlspecialchars($book['cover_image_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        </a>
                        <div class="title-author-price">
                            <div class="title-author">
                                <h1 title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h1>
                                <h2><?= htmlspecialchars($book['author_name']) ?></h2>
                            </div>
                            <h1 class="price">€ <?= number_format($book['price'], 2) ?></h1>
                        </div>
                        <p class="book-category"><?= htmlspecialchars($book['category_name'] ?? 'Uncategorized') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    <?php endforeach; ?>
</div>



<div class="mood-selection">
    <h2>How are you feeling today?</h2>
    <div id="moodButtons" class="mood-buttons">
        <button class="mood-btn" data-mood="happy"><i class="fas fa-smile"></i> Happy</button>
        <button class="mood-btn" data-mood="sad"><i class="fas fa-sad-tear"></i> Sad</button>
        <button class="mood-btn" data-mood="motivated"><i class="fas fa-bolt"></i> Motivated</button>
        <button class="mood-btn" data-mood="romantic"><i class="fas fa-heart"></i> Romantic</button>
        <button class="mood-btn" data-mood="adventurous"><i class="fas fa-hiking"></i> Adventurous</button>
    </div>
</div>

<div id="moodSentence"></div>
<div id="moodBooks" class="books"></div>


<div id="moodLoading" style="display:none; text-align:center; margin-top:-40px;">
    <i class="fas fa-cog fa-spin" style="font-size:32px; color:#666;"></i>
    <p style="margin-top:10px; font-size:18px; color:#555;">Loading recommendations...</p>
</div>




<script src="js/moodRecommender.js"></script>
<script src="js/main.js?v=1.2"></script>
</body>
</html>
