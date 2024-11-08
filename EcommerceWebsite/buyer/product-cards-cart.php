<?php
include "authguard.php";
include "menu.php";
include "../connection.php";

$productName = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$sellerEmail = isset($_GET['email']) ? urldecode($_GET['email']) : '';

$sql = "SELECT * FROM product WHERE name = '$productName' AND email = '$sellerEmail'";
$sql_result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($sql_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding-top: 80px;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 30px;
            background: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: row;
            align-items: flex-start;
        }

        .product-image {
            width: 500px;
            height: auto;
            border-radius: 5px;
            margin-right: 30px;
        }

        .details {
            flex-grow: 1;
        }

        .product-name {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
            text-align: left;
        }

        .property {
            margin: 20px 0;
        }

        .property label {
            font-weight: bold;
        }

        .property-value {
            display: block;
            margin-top: 5px;
            color: #c0c0c0;
        }

        .btn-danger {
            background-color: #d32f2f;
            border: none;
            color: #e0e0e0;
            width: 100%;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            transform: scale(1);
        }

        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #b71c1c;
            transform: scale(1.05);
        }

        .back-link {
            color: #303f9f;
            text-decoration: underline;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="<?php echo htmlspecialchars($product['impath']); ?>" alt="Product Image" class="product-image">
    
    <div class="details">
        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>

        <div class="property">
            <label>Price:</label>
            <span class="property-value"><?php echo htmlspecialchars($product['price']); ?> Rs</span>
        </div>

        <div class="property">
            <label>Description:</label>
            <span class="property-value"><?php echo htmlspecialchars($product['detail']); ?></span>
        </div>

        <div class="property">
            <label>Seller Email:</label>
            <span class="property-value"><?php echo htmlspecialchars($sellerEmail); ?></span>
        </div>

        <div class="property">
            <label>Mobile:</label>
            <span class="property-value"><?php echo htmlspecialchars($product['mobile']); ?></span>
        </div>

        <div class="property">
            <label>Address:</label>
            <span class="property-value"><?php echo htmlspecialchars($product['address1'] . ', ' . $product['address2'] . ', ' . $product['country']); ?></span>
        </div>
        
        <form action='deletecart.php' method='get'>
            <input type='hidden' name='cartid' value='<?php echo htmlspecialchars($product['product_id']); ?>'>
            <input type='hidden' name='ownerid' value='<?php echo htmlspecialchars($product['owner']); ?>'>
            <button class='btn-danger'>Remove from Cart</button>
        </form>

        <div class="back-link" onclick="window.history.back()">Back</div>
    </div>
</div>

</body>
</html>