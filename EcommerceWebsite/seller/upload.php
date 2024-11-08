<?php

session_start();

$source = $_FILES["image"]["tmp_name"];
$target = "../shared/images/" . $_FILES["image"]["name"];

move_uploaded_file($source, $target);

include "../connection.php";

$userid = $_SESSION['userid'];

$query = "SELECT mobile, email, address1, address2, country FROM user WHERE userid = $userid";
$result = mysqli_query($conn, $query);
$details = mysqli_fetch_assoc($result);

$mobile = $details['mobile'];
$email = $details['email'];
$address1 = $details['address1'];
$address2 = $details['address2'];
$country = $details['country'];

$sql = "INSERT INTO product (name, price, detail, impath, owner, mobile, email, address1, address2, country) 
        VALUES ('$_POST[name]', '$_POST[price]', '$_POST[detail]', '$target', $userid, '$mobile', '$email', '$address1', '$address2', '$country')";

if (mysqli_query($conn, $sql)) {
    header("Location: /seller/view.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
