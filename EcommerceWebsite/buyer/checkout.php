<?php
session_start();
include "../connection.php";

if (!isset($_SESSION['userid'])) {
    echo "Please log in to place an order.";
    exit;
}

$userId = $_SESSION['userid'];

$totalQuery = "SELECT SUM(product.price * cart.quantity) AS total_amount 
               FROM cart 
               JOIN product ON cart.product_id = product.product_id 
               WHERE cart.userid = $userId";
$totalResult = mysqli_query($conn, $totalQuery);

if (!$totalResult || mysqli_num_rows($totalResult) == 0) {
    echo "Your cart is empty.";
    exit;
}

$totalRow = mysqli_fetch_assoc($totalResult);
$totalAmount = $totalRow['total_amount'];

if ($totalAmount <= 0) {
    echo "Invalid total amount.";
    exit;
}

$userDetailsQuery = "SELECT product.name AS item_name, cart.quantity, product.owner AS Suser_id, user.mobile AS Smobile, user.email AS Semail, user.address1 AS Saddress1, user.address2 AS Saddress2, user.country AS Scountry
                     FROM cart 
                     JOIN product ON cart.product_id = product.product_id
                     JOIN user ON product.owner = user.userid
                     WHERE cart.userid = $userId";
$userDetailsResult = mysqli_query($conn, $userDetailsQuery);

if (!$userDetailsResult || mysqli_num_rows($userDetailsResult) == 0) {
    echo "No products found in your cart.";
    exit;
}

$orderItems = [];
while ($userDetails = mysqli_fetch_assoc($userDetailsResult)) {
    $orderItems[] = $userDetails;
}

$buyerDetailsQuery = "SELECT mobile, email, address1, address2, country 
                      FROM user 
                      WHERE userid = $userId AND usertype = 'buyer'";
$buyerDetailsResult = mysqli_query($conn, $buyerDetailsQuery);

if (!$buyerDetailsResult || mysqli_num_rows($buyerDetailsResult) == 0) {
    echo "No buyer details found.";
    exit;
}

$buyerDetails = mysqli_fetch_assoc($buyerDetailsResult);

foreach ($orderItems as $item) {
    $orderSql = "INSERT INTO orders (user_id, total_amount, items, quantity, Suser_id, Smobile, Semail, Saddress1, Saddress2, Scountry, Bmobile, Bemail, Baddress1, Baddress2, Bcountry, status, order_date)
                 VALUES (
                     $userId, 
                     $totalAmount, 
                     '{$item['item_name']}', 
                     '{$item['quantity']}', 
                     '{$item['Suser_id']}', 
                     '{$item['Smobile']}', 
                     '{$item['Semail']}', 
                     '{$item['Saddress1']}', 
                     '{$item['Saddress2']}', 
                     '{$item['Scountry']}', 
                     '{$buyerDetails['mobile']}', 
                     '{$buyerDetails['email']}', 
                     '{$buyerDetails['address1']}', 
                     '{$buyerDetails['address2']}', 
                     '{$buyerDetails['country']}', 
                     'Pending', 
                     NOW()
                 )";

        
        $_SESSION['order_message'] = "Order placed successfully!";


    if (!mysqli_query($conn, $orderSql)) {
        echo "Error placing order: " . mysqli_error($conn);
        exit;
    }
}

$clearCartSql = "DELETE FROM cart WHERE userid = $userId";
if (mysqli_query($conn, $clearCartSql)) {
    header("Location: /buyer/viewcart.php");
    exit;
} else {
    echo "Error clearing the cart: " . mysqli_error($conn);
}
?>
