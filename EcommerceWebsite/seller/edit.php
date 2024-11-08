<?php

include "../connection.php";
include "authguard.php";
include "menu.php";

if (!isset($_POST['product_id'])) {
    echo "Product ID not specified.";
    exit;
}

$product_id = $_POST['product_id'];

$sql = "SELECT * FROM product WHERE product_id = '$product_id' AND owner = '$_SESSION[userid]'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Product not found.";
    exit;
}

$product = mysqli_fetch_assoc($result);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Edit Your profile</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding-top: 70px;
        }
        .form-container {
            background: linear-gradient(135deg, #333333, #1a1a1a);
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
            margin: 5% auto;
            padding: 20px;
            max-width: 600px;
            color: #e0e0e0;
        }
        .form-control {
            background-color: #1a1a1a;
            color: #e0e0e0;
            border: 1px solid #333;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #666;
            outline: none;
        }
        .form-label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .buttons button {
            background-color: #d32f2f;
            border: none;
            color: #e0e0e0;
            padding: 12px; 
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }

        .buttons button:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
    <div class='form-container'>
        <h1>Edit Product</h1>
        <form action='updated-product.php' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='product_id' value='" . htmlspecialchars($product['product_id']) . "'>

            <label class='form-label' for='name'>Product Name:</label>
            <input class='form-control' type='text' autocomplete='off' id='name' name='name' value='" . htmlspecialchars($product['name']) . "' required>

            <label class='form-label' for='price'>Price:</label>
            <input class='form-control' type='number' id='price' name='price' value='" . htmlspecialchars($product['price']) . "' required>

            <label class='form-label' for='detail'>Details:</label>
            <textarea class='form-control' id='detail' name='detail' required>" . htmlspecialchars($product['detail']) . "</textarea>

            <label class='form-label' for='image'>Product Image:</label>
            <input class='form-control' type='file' id='image' name='image' required>

            <div class='buttons'><button type='submit'>Update Product</button><div>
        </form>
    </div>
</body>
</html>";

?>
