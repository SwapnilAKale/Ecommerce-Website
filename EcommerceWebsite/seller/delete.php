<?php

session_start();

include "../connection.php";

$deleteQuery = "DELETE FROM product WHERE product_id = '$_POST[product_id]' AND owner = $_SESSION[userid]";
$result = mysqli_query($conn, $deleteQuery);

header("location: /seller/view.php");

?>
