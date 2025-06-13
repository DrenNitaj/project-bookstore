<?php
// Include the session check script
include('sessionCheck.php');

// connect with database
include_once("config.php");

    
    
if(empty($_SESSION['user_id'])){
    header("Location: signin.php");
}



if (!isset($_GET['book_id'])) {
    header('Location: index.php');
    exit;
}

$book_id = (int)$_GET['book_id'];


// Fetch book details
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if ($book) {
    $maxAvailable = min(5, (int)$book['stock_quantity']);
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
            <link rel="stylesheet" type="text/css" href="css/book.css?v=1.1">
            <title>the BookHouse / <?php echo $book['title'];?></title>
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



        <div class="purchase-container">
            <h1>Complete Your Purchase</h1>
            <div class="purchasediv">
                <div class="book-summary">
                    <img src="images/coverimages/<?= $book['cover_image_url'] ?>" alt="Book cover">
                    <div>
                        <h2><?= htmlspecialchars($book['title']) ?></h2>
                        <h3>by <?= htmlspecialchars($book['author_name']) ?></h3>
                        <p>Price: <strong>€<?= number_format($book['price'], 2) ?></strong></p>
                        <p><?= htmlspecialchars($book['description']) ?></p>
                    </div>
                </div>

                <form method="POST" action="processPurchase.php">
                    <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                    <input type="hidden" name="price" value="<?= $book['price'] ?>">

                    <div class="input-div">
                        <input class="input" type="number" name="quantity" placeholder=" " min="1" max="<?= $maxAvailable ?>" value="1" required>
                        <label>Quantity</label>
                    </div>
                    <p style="font-size: 0.9rem; color: #555; margin-top: -8px;">
                        Max allowed: <?= $maxAvailable ?>
                    </p>

                    <div class="input-div">
                        <select class="input" name="delivery_method" required>
                            <option value="" disabled selected hidden></option>
                            <option value="standard">Standard (Free)</option>
                            <option value="express">Express (€4.99)</option>
                        </select>
                        <label>Delivery Method</label>
                    </div>

                    <div class="input-div">
                        <textarea class="input" name="shipping_address" placeholder=" " rows="3" required></textarea>
                        <label>Shipping Address</label>
                    </div>

                    <div class="input-div">
                        <input class="input" type="text" name="cardholder_name" placeholder=" " required>
                        <label>Cardholder Name</label>
                    </div>

                    <div class="input-div">
                        <input class="input" type="text" name="card_number" placeholder=" " pattern="\d{13,19}" maxlength="19" inputmode="numeric" required>
                        <label>Card Number</label>
                    </div>

                    <div class="input-div">
                        <input class="input" type="month" name="expiry_date" required>
                        <label>Expiry Date (MM/YYYY)</label>
                    </div>

                    <div class="input-div">
                        <input class="input" type="text" name="cvv" placeholder=" " pattern="\d{3,4}" maxlength="4" inputmode="numeric" required>
                        <label>CVV</label>
                    </div>

                    <div class="total-preview">
                        <p><strong>Total:</strong> €<span id="totalDisplay"><?= number_format($book['price'], 2) ?></span></p>
                    </div>

                    <button type="submit">Confirm Purchase</button>
                </form>
            </div>
        </div>


<script>
    const form = document.querySelector('form');
    const quantityInput = form.querySelector('input[name="quantity"]');
    const deliverySelect = form.querySelector('select[name="delivery_method"]');
    const cardNumberInput = form.querySelector('input[name="card_number"]');
    const cvvInput = form.querySelector('input[name="cvv"]');

    const maxAllowed = <?= $maxAvailable ?>;

    function updateTotal() {
        let quantity = parseInt(quantityInput.value) || 1;

        if (quantity > maxAllowed) {
            quantity = maxAllowed;
            quantityInput.value = maxAllowed;
        } else if (quantity < 1) {
            quantity = 1;
            quantityInput.value = 1;
        }

        const delivery = deliverySelect.value;
        let deliveryCost = (delivery === 'express') ? 4.99 : 0;

        const total = (bookPrice * quantity) + deliveryCost;
        totalDisplay.textContent = total.toFixed(2);
    }

    quantityInput.addEventListener('input', updateTotal);
    deliverySelect.addEventListener('change', updateTotal);

    quantityInput.addEventListener('keydown', function (e) {
        // Optional hard restriction if you want to block keys like e, -, +, etc.
        if (e.key === "e" || e.key === "-" || e.key === "+") {
            e.preventDefault();
        }
    });

    // Custom validity messages on input
    cardNumberInput.addEventListener('input', () => {
        if (!/^\d{13,19}$/.test(cardNumberInput.value.trim())) {
            cardNumberInput.setCustomValidity("Card number must be between 13 and 19 digits.");
        } else {
            cardNumberInput.setCustomValidity("");
        }
    });

    cvvInput.addEventListener('input', () => {
        if (!/^\d{3,4}$/.test(cvvInput.value.trim())) {
            cvvInput.setCustomValidity("CVV must be 3 or 4 digits.");
        } else {
            cvvInput.setCustomValidity("");
        }
    });

    form.addEventListener('submit', function (e) {
        const quantity = parseInt(quantityInput.value);
        const cardNumber = cardNumberInput.value.trim();
        const cvv = cvvInput.value.trim();
        const delivery = deliverySelect.value;
        const address = form.querySelector('textarea[name="shipping_address"]').value.trim();
        const cardholder = form.querySelector('input[name="cardholder_name"]').value.trim();

        if (isNaN(quantity) || quantity < 1 || quantity > <?= $maxAvailable ?>) {
            alert("Please select a quantity between 1 and <?= $maxAvailable ?>.");
            e.preventDefault();
            return;
        }

        if (!['standard', 'express'].includes(delivery)) {
            alert("Please select a valid delivery method.");
            e.preventDefault();
            return;
        }

        if (address.length === 0 || cardholder.length === 0) {
            alert("Please fill in all required fields.");
            e.preventDefault();
            return;
        }

        if (!cardNumberInput.checkValidity()) {
            cardNumberInput.reportValidity();
            e.preventDefault();
            return;
        }

        if (!cvvInput.checkValidity()) {
            cvvInput.reportValidity();
            e.preventDefault();
            return;
        }

        // Optionally allow expiry date check, or skip it based on your note
    });

    const totalDisplay = document.getElementById('totalDisplay');
    const bookPrice = parseFloat(<?= json_encode($book['price']) ?>);




    let timeoutDuration = 1800000; // 30 minutes in milliseconds
    let timeout;

    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            location.reload();
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
    } else {
        echo "Book not found.";
    }
?>
