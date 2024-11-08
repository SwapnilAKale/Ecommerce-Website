<?php
include "authguard.php";
include "menu.php";
include "../connection.php";

$productName = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$sellerEmail = isset($_GET['email']) ? urldecode($_GET['email']) : '';

$sql = "SELECT * FROM product WHERE name = '$productName' AND email = '$sellerEmail'";
$sql_result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($sql_result);

$userid = $_SESSION['userid'];
$user_sql = "SELECT mobile, email, address1, address2, country FROM user WHERE userid = $userid";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

$missingFields = [];
if (empty($user['mobile'])) $missingFields[] = 'Mobile number';
if (empty($user['email'])) $missingFields[] = 'Email';
if (empty($user['address1'])) $missingFields[] = 'Address';
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
            padding: 20px;
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
            margin-right: 20px;
        }

        .details {
            flex-grow: 1;
        }

        .product-name {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
            text-align: left;
        }

        .property {
            margin: 15px 0;
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
        .profile-popup {
            background-color: #0066dd;
            color: #e0e0e0;
            padding: 10px 20px;
            border-radius: 5px;
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 1000;
            animation: fadeInOut 3s forwards;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-20px); }
        }
    </style>
    <script>
        function addToCart(productId) {
            const missingFields = <?php echo json_encode($missingFields); ?>;
            if (missingFields.length > 0) {
                const message = "Complete your profile to add to cart. Missing: <br>" + missingFields.join(", ");
                const popup = document.createElement('div');
                popup.className = 'profile-popup';
                popup.innerHTML = message;
                document.body.appendChild(popup);

                setTimeout(() => {
                    popup.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(popup);
                    }, 300);
                }, 3000);
            } else {
                window.location.href = 'addcart.php?pid=' + productId;
            }
        }
    </script>
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
        
        <button class="btn-danger" onclick="addToCart(<?php echo $product['product_id']; ?>)">Add to Cart</button>
        <div class="back-link" onclick="window.history.back()">Back</div>
    </div>
</div>

</body>
</html>
