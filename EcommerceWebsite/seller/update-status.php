<?php
session_start();
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    if (!empty($orderId) && !empty($newStatus)) {
        $updateQuery = "UPDATE orders SET status = '$newStatus' WHERE order_id = $orderId AND Suser_id = {$_SESSION['userid']}";

        if (mysqli_query($conn, $updateQuery)) {
            header("Location: vieworders.php");
        } else {
            echo "Error updating status: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid input.";
    }
} else {
    header("Location: vieworders.php");
}
?>
