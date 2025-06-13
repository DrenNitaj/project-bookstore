<?php
session_start();
include('sessionCheck.php');
include_once("config.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: signinAdmin.php");
}

// Fetch all purchases with user and book info
$sql = "
    SELECT 
        p.purchase_id, u.name, u.surname, p.purchase_date, p.total_amount, 
        p.delivery_method, p.shipping_address, p.status,
        b.title AS book_title, b.cover_image_url AS cover_image_url
    FROM purchases p
    JOIN users u ON p.user_id = u.user_id
    JOIN purchase_items pi ON p.purchase_id = pi.purchase_id
    JOIN books b ON pi.book_id = b.book_id
    ORDER BY p.purchase_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>the BookHouse / Purchases</title>
    <link rel="icon" href="images/logo.jpg">
</head>
<body>

<header>
    <div class="logo">
        <img src="images/logo1.png" alt="Logo">
        <h1>the BookHouse</h1>
    </div>
    <ul>
        <li><a href="addBooks.php">BOOKS</a></li>
        <li class="active"><a href="purchasesAdmin.php">PURCHASES</a></li>
        <li><a href="users.php">USERS</a></li>
        <li><a action="logoutAdmin.php" href="logoutAdmin.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#a0a0a0">
                <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/>
            </svg> LOG OUT
        </a></li>
    </ul>
    <div class="menu-toggle">&#9776;</div>    
</header>

<div style="margin-top: 120px;" class="table purchases-table" id="books">
    <div class="heading-searchBox purchases-heading-searchBox">
        <h1 class="heading">Purchases</h1>
        <div class="purchases-filter">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter">
                <option value="processed" selected>Processed</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="declined">Declined</option>
                <option value="refunded">Refunded</option>
                <option value="all">All</option>
            </select>

            <label for="deliveryFilter">Delivery:</label>
            <select id="deliveryFilter">
                <option value="all" selected>All</option>
                <option value="standard">Standard</option>
                <option value="express">Express</option>
            </select>
        </div>
    </div>

    <table id="table">
        <thead>
            <tr>
                <th>Purchase ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total (€)</th>
                <th>Delivery</th>
                <th>Shipping Address</th>
                <th>Current Status</th>
                <th>Book Title</th>
                <th>Cover Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $purchase): ?>
                <tr class="clickable-row" data-href="purchaseDetails.php?purchase_id=<?php echo $purchase['purchase_id']; ?>">
                    <td data-label="Purchase ID"><?php echo $purchase['purchase_id']; ?></td>
                    <td data-label="Customer"><?php echo htmlspecialchars($purchase['name'] . ' ' . $purchase['surname']); ?></td>
                    <td data-label="Date"><?php echo $purchase['purchase_date']; ?></td>
                    <td data-label="Total"><?php echo $purchase['total_amount']; ?> €</td>
                    <td data-label="Delivery"><?php echo ucfirst($purchase['delivery_method']); ?></td>
                    <td data-label="Shipping Address"><?php echo htmlspecialchars($purchase['shipping_address']); ?></td>
                    <td data-label="Current Status"><?php echo ucfirst($purchase['status']); ?></td>
                    <td data-label="Book Title"><?php echo htmlspecialchars($purchase['book_title']); ?></td>
                    <td class="cover-image-td" data-label="Cover Image">
                        <?php if (!empty($purchase['cover_image_url'])): ?>
                            <img src="images/coverimages/<?php echo $purchase['cover_image_url']; ?>" alt="Book Cover" class="cover-image">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td data-label="Action">
                        <?php
                            $status = $purchase['status'];
                            $purchaseId = $purchase['purchase_id'];
                            if ($status === 'processed') {
                                echo "<a href=\"#\" class=\"action-link\" data-purchase-id=\"$purchaseId\" data-action=\"complete\">Complete</a> | ";
                                echo "<a href=\"#\" class=\"action-link\" data-purchase-id=\"$purchaseId\" data-action=\"decline\">Decline</a>";
                            } elseif ($status === 'declined') {
                                echo "<a href=\"#\" class=\"action-link\" data-purchase-id=\"$purchaseId\" data-action=\"refund\">Refund</a>";
                            } else {
                                echo "—";
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
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


<div class="alert" id="completed-status-alert">  
    <div>
       <h1>The purchase has been successfully marked as completed.</h1>
    </div>                       
</div>

<div class="alert" id="declined-status-alert">  
    <div>
       <h1>The purchase has been successfully declined and will not be processed.</h1>
    </div>                       
</div>

<div class="alert" id="refunded-status-alert">  
    <div>
       <h1>The purchase has been refunded successfully.</h1>
    </div>                       
</div>













<script>
    document.addEventListener('DOMContentLoaded', function() {
        const url = new URL(window.location);
        const action = url.searchParams.get('action');

        if (action === 'complete') {
            showAlert('completed-status-alert');
            setTimeout(hideAlert, 3000);
            removeUrlParams();
        } else if (action === 'decline') {
            showAlert('declined-status-alert');
            setTimeout(hideAlert, 3000);
            removeUrlParams();
        } else if (action === 'refund') {
            showAlert('refunded-status-alert');
            setTimeout(hideAlert, 3000);
            removeUrlParams();
        }

        function showAlert(id) {
            const alert = document.getElementById(id);
            if (alert) {
                alert.style.display = 'flex';
            }
        }

        function hideAlert() {
            const alerts = [
                'completed-status-alert',
                'declined-status-alert',
                'refunded-status-alert'
            ];
            alerts.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
        }

        function removeUrlParams() {
            const url = new URL(window.location);
            url.searchParams.delete('status_updated');
            url.searchParams.delete('action');
            window.history.replaceState({}, document.title, url);
        }
    });









    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.querySelector('.overlay');
        const noLink = document.querySelector('.no');
        const yesLink = document.querySelector('.yes');
        let actionType = null;
        let currentPurchaseId = null;

        document.querySelectorAll('.action-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                actionType = this.dataset.action;
                currentPurchaseId = this.dataset.purchaseId;
                overlay.classList.add('showww');
            });
        });

        noLink.addEventListener('click', function () {
            overlay.classList.remove('showww');
        });

        yesLink.addEventListener('click', function () {
            if (currentPurchaseId && actionType) {
                window.location.href = `updatePurchaseStatus.php?action=${actionType}&purchase_id=${currentPurchaseId}`;
            }
        });
    });









    document.addEventListener('DOMContentLoaded', function () {
        const statusFilter = document.getElementById('statusFilter');
        const deliveryFilter = document.getElementById('deliveryFilter');
        const rows = document.querySelectorAll('#table tbody tr');
        const noResults = document.getElementById('no-results');

        function applyFilters() {
            const selectedStatus = statusFilter.value.toLowerCase();
            const selectedDelivery = deliveryFilter ? deliveryFilter.value.toLowerCase() : 'all';
            let visibleCount = 0;

            rows.forEach(row => {
                const statusCell = row.querySelector('td[data-label="Current Status"]');
                const deliveryCell = row.querySelector('td[data-label="Delivery"]');

                const rowStatus = statusCell ? statusCell.textContent.trim().toLowerCase() : '';
                const rowDelivery = deliveryCell ? deliveryCell.textContent.trim().toLowerCase() : '';

                const statusMatches = selectedStatus === 'all' || rowStatus === selectedStatus;
                const deliveryMatches = selectedDelivery === 'all' || rowDelivery === selectedDelivery;

                if (statusMatches && deliveryMatches) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        statusFilter.addEventListener('change', applyFilters);
        if (deliveryFilter) {
            deliveryFilter.addEventListener('change', applyFilters);
        }

        // Initial filter + status styling
        applyFilters();

        // Also color the statuses
        document.querySelectorAll('td[data-label="Current Status"]').forEach(td => {
            const statusText = td.textContent.trim().toLowerCase();
            let color = '', background = '';

            switch (statusText) {
                case 'processed':
                    color = '#007bff';
                    break;
                case 'completed':
                    color = '#2e7d32';
                    break;
                case 'failed':
                    color = '#dc3545';
                    break;
                case 'declined':
                    color = '#6c757d';
                    break;
                case 'refunded':
                    color = '#ffc107';
                    break;
                default:
                    color = '#000';
            }

            td.style.color = color;
            td.style.fontWeight = 'bold';
            td.style.borderRadius = '5px';
            td.style.textAlign = 'left';
        });
    });












    let timeoutDuration = 1800000;
    let timeout;
    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {}, timeoutDuration);
    }
    window.onload = resetTimeout;
    document.onmousemove = resetTimeout;
    document.onkeypress = resetTimeout;
    document.onclick = resetTimeout;
</script>
<script type="text/javascript" src="js/main.js?v=1.1"></script>
</body>
</html>
