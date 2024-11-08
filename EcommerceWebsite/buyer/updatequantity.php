<?php
include "../connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['userid'];
    $action = $_POST['action'];

    $query = "SELECT quantity FROM cart WHERE product_id = '$productId' AND userid = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $currentQuantity = $row['quantity'];

        if ($action === 'increment') {
            $newQuantity = $currentQuantity + 1;
        } elseif ($action === 'decrement' && $currentQuantity > 1) {
            $newQuantity = $currentQuantity - 1;
        } else {
            $newQuantity = 0;
        }

        if ($newQuantity > 0) {
            $updateQuery = "UPDATE cart SET quantity = '$newQuantity' WHERE product_id = '$productId' AND userid = '$userId'";
            mysqli_query($conn, $updateQuery);
        } else {
            $deleteQuery = "DELETE FROM cart WHERE product_id = '$productId' AND userid = '$userId'";
            mysqli_query($conn, $deleteQuery);
        }
    }

    header("Location: viewcart.php");
    exit;
}
?>
