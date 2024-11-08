<?php
session_start();

include '../connection.php';

if (!isset($_SESSION['userid'])) {
    header("Location: /shared/login.html");
    exit();
}

$user_id = $_SESSION['userid'];

$removeFromCartQuery = "DELETE cart.* FROM cart JOIN product ON cart.product_id = product.product_id WHERE cart.user_id = '$user_id'";
mysqli_query($conn, $removeFromCartQuery);

$deleteProductsQuery = "DELETE FROM orders WHERE user_id = '$user_id'";
mysqli_query($conn, $deleteProductsQuery);

$deleteUserQuery = "DELETE FROM user WHERE userid = '$user_id'";
$result = mysqli_query($conn, $deleteUserQuery);

if ($result) {
    session_destroy();
    header("Location: /shared/login.html");
    exit();
} else {
    echo "Error deleting account: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
