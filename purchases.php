<?php

// Include the session check script
include('sessionCheck.php');

// connect with database (uses $conn = new PDO(...) in config.php)
include_once("config.php");

if(empty($_SESSION['user_id'])){
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch purchases and related books using PDO
$query = "
    SELECT 
        p.purchase_id, 
        p.purchase_date, 
        p.total_amount,
        p.shipping_address,
        p.delivery_method,
        p.status,
        b.book_id,
        b.title,
        b.price,
        b.cover_image_url,
        b.author_name
    FROM purchases p
    LEFT JOIN purchase_items pi ON p.purchase_id = pi.purchase_id
    LEFT JOIN books b ON pi.book_id = b.book_id
    WHERE p.user_id = ?
    ORDER BY p.purchase_date DESC
";

$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group books by purchase_id
$purchases = [];
foreach ($results as $row) {
    $purchase_id = $row['purchase_id'];
    if (!isset($purchases[$purchase_id])) {
        $purchases[$purchase_id] = [
            'date' => $row['purchase_date'],
            'total' => $row['total_amount'],
            'shipping_address' => $row['shipping_address'],
            'delivery_method' => $row['delivery_method'],
            'status' => $row['status'],
            'books' => []
        ];
    }
    $purchases[$purchase_id]['books'][] = [
        'book_id' => $row['book_id'],
        'title' => $row['title'],
        'price' => $row['price'],
        'author_name' => $row['author_name'],
        'cover_image_url' => $row['cover_image_url']
    ];
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
    <link rel="stylesheet" type="text/css" href="css/cart-wishlist.css?v=1.1">
    <title>the BookHouse / Purchases</title>
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
            <li class="active"><a href="purchases.php">PURCHASES</a></li>
            <li><a href="account.php" id="user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg> MY PROFILE</a></li>
            <li><a action="logout.php" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>  LOG OUT</a></li>
		</ul>
        <div class="menu-toggle">&#9776;</div>    
	</header>

    <main class="purchase-container" style="padding: 2rem;">
        <h2 style="font-size: 28px;">My Purchases</h2>

        <?php if (empty($purchases)): ?>
            <h1 id="no-cart-items" class="heading">You haven't purchased any books yet.</h1>
        <?php else: ?>
            <?php foreach ($purchases as $purchase_id => $purchase): ?>
                <?php
                    // Determine status class and label
                    $status = strtolower(trim($purchase['status'] ?? 'processed')); // default to 'processed'

                    $status_classes = [
                        'processed' => 'status-processed',
                        'completed' => 'status-completed',
                        'failed' => 'status-failed',
                        'declined' => 'status-declined',
                        'refunded' => 'status-refunded'
                    ];

                    $status_class = $status_classes[$status] ?? 'status-processed';
                ?>
                <div class="purchase-card <?= $status_class ?>">
                    <!-- Book cover (first book shown) -->
                    <?php $firstBook = $purchase['books'][0]; ?>
                    <div class="book-cover-info">
                        <a href="signinBook.php?book_id=<?= urlencode($firstBook['book_id']) ?>" class="book-card-link">
                            <div class="book-cover">
                                <img src="images/coverimages/<?= htmlspecialchars($firstBook['cover_image_url']) ?>" alt="<?= htmlspecialchars($firstBook['title']) ?>">
                            </div>
                        </a>

                        <!-- Book info -->
                        <div class="book-info">
                            <h3><?= htmlspecialchars($firstBook['title']) ?></h3>
                            <p class="author">by <?= htmlspecialchars($firstBook['author_name']) ?></p>
                            <p><strong>Price:</strong> $<?= number_format($firstBook['price'], 2) ?></p>
                            <?php if (count($purchase['books']) > 1): ?>
                                <p style="color: gray;">+ <?= count($purchase['books']) - 1 ?> more book<?= count($purchase['books']) > 2 ? 's' : '' ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    

                    <!-- Purchase info -->
                    <div class="purchase-details">
                        <p><strong>Date:</strong><br><?= date('F j, Y, g:i a', strtotime($purchase['date'])) ?></p>
                        <div class="purchase-meta-row">
                            <div><strong>Total:</strong><br>$<?= number_format($purchase['total'], 2) ?></div>
                            <div><strong>Shipping:</strong><br><?= htmlspecialchars($purchase['shipping_address']) ?></div>
                            <div><strong>Delivery:</strong><br><?= htmlspecialchars($purchase['delivery_method']) ?></div>
                        </div>
                        <p><strong>Status:</strong><br><span class="status-label"><?= ucfirst($status) ?></span></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>


    <script>
        let timeoutDuration = 1800000;
        let timeout;

        function resetTimeout() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                location.reload();
            }, timeoutDuration);
        }

        window.onload = resetTimeout;
        document.onmousemove = resetTimeout;
        document.onkeypress = resetTimeout;
        document.onclick = resetTimeout;
    </script>
    <script type="text/javascript" src="js/main.js?v=1.1"></script>
</body>
</html>
