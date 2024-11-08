<?php
session_start();

include "../connection.php";

$cartid = $_GET['cartid']; 
$userid = $_SESSION['userid'];

$query = "DELETE FROM cart WHERE product_id='$cartid' AND userid='$userid'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
} else {
    header("Location: viewcart.php");
    exit();
}
?>
