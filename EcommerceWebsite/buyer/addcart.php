<?php
session_start();

$pid = $_GET['pid'];
$userId = $_SESSION['userid'];

include "../connection.php";

$sql_check = "SELECT * FROM cart WHERE userid = $userId AND product_id = '$pid'";
$result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
    $row = mysqli_fetch_assoc($result_check);
    $newQuantity = $row['quantity'] + 1;
    $cartId = $row['cart_id'];

    $sql_update = "UPDATE cart SET quantity = $newQuantity WHERE cart_id = $cartId";
    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['cart_message'] = "Product quantity updated in your cart.";
        $_SESSION['cart_action'] = 'updated';
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating quantity: " . mysqli_error($conn);
    }
} else {
    $sql_insert = "INSERT INTO cart (name, quantity, price, impath, detail, userid, user_id, product_id, mobile, email, address1, address2, country) 
                   SELECT p.name, 1, p.price, p.impath, p.detail, '$userId', p.owner, p.product_id, p.mobile, p.email, p.address1, p.address2, p.country
                   FROM product p 
                   WHERE p.product_id = '$pid'";

    if (mysqli_query($conn, $sql_insert)) {
        $_SESSION['cart_message'] = "New product added to your cart.";
        $_SESSION['cart_action'] = 'added';
        header("Location: home.php");
        exit();
    } else {
        echo "Error inserting into cart: " . mysqli_error($conn);
    }
}
?>
