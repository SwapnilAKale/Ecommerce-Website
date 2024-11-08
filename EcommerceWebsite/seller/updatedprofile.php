<?php

include '../connection.php';

session_start();

$userId = $_SESSION['userid'];
$newMobile = $_POST['mobile'];
$newEmail = $_POST['email'];
$newAddress1 = $_POST['address1'];
$newAddress2 = $_POST['address2'];
$newCountry = $_POST['country'];

if (!empty($_FILES["image"]["tmp_name"])) {
    $source = $_FILES["image"]["tmp_name"];
    $filename = $_FILES["image"]["name"];
    $target = "../shared/profileimages/" . $filename;

    if (move_uploaded_file($source, $target)) {
        $sql = "UPDATE user 
                SET email = '$newEmail', 
                    mobile = '$newMobile', 
                    address1 = '$newAddress1', 
                    address2 = '$newAddress2', 
                    country = '$newCountry',
                    impath = '$target'
                WHERE userid = $userId";
    } else {
        echo "Error uploading the image.";
        exit;
    }
} else {
    $sql = "UPDATE user 
            SET email = '$newEmail', 
                mobile = '$newMobile', 
                address1 = '$newAddress1', 
                address2 = '$newAddress2', 
                country = '$newCountry'
            WHERE userid = $userId";
}

if (mysqli_query($conn, $sql)) {
    header("Location: /seller/home.php");
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

?>
