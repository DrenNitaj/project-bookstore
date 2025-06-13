<?php
session_start();
include('sessionCheck.php');
include_once("config.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: signinAdmin.php");
    exit();
}

if (isset($_GET['action'], $_GET['purchase_id'])) {
    $action = $_GET['action'];
    $purchase_id = (int) $_GET['purchase_id'];

    // Define allowed actions and their corresponding statuses
    $actionToStatus = [
        'complete' => 'completed',
        'decline' => 'declined',
        'refund' => 'refunded'
    ];

    if (!array_key_exists($action, $actionToStatus)) {
        die("Invalid action.");
    }

    $new_status = $actionToStatus[$action];

    // Update the purchase status
    $stmt = $conn->prepare("UPDATE purchases SET status = :status WHERE purchase_id = :purchase_id");
    $stmt->bindParam(':status', $new_status);
    $stmt->bindParam(':purchase_id', $purchase_id);

    if ($stmt->execute()) {
        header("Location: purchasesAdmin.php?status_updated=1&action=$action");
        exit();
    } else {
        die("Failed to update purchase status.");
    }
} else {
    die("Missing parameters.");
}
