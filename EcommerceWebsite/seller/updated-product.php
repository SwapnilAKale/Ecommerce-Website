<?php

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $source = $_FILES['image']['tmp_name'];
    $image_name = basename($_FILES['image']['name']);
    $target = "../shared/images/" . $image_name;

    if (move_uploaded_file($source, $target)) {
        $impath = $target;
    } else {
        echo "Error uploading file.";
        exit;
    }
} else {
    $sql = "SELECT impath FROM product WHERE product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $impath = $row['impath'];
    } else {
        echo "Error fetching current image path: " . mysqli_error($conn);
        exit;
    }
}

include "../connection.php";

mysqli_query($conn, "UPDATE product SET name='$name', price='$_POST[price]', detail='$_POST[detail]', impath='$impath' WHERE product_id='$_POST[product_id]' AND owner='$_SESSION[userid]'");

header("location: /seller/view.php");

?>
