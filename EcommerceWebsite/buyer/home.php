<?php
include "authguard.php";
include "menu.php";
include "../connection.php";

$showCartPopup = false;
if (isset($_SESSION['cart_added']) && $_SESSION['cart_added']) {
    $showCartPopup = true;
    unset($_SESSION['cart_added']);
}

$userid = $_SESSION['userid'];
$sql = "SELECT mobile, email, address1, address2, country FROM user WHERE userid = $userid";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$missingFields = [];

if (empty($user['mobile'])) {
    $missingFields[] = 'Mobile number';
}
if (empty($user['email'])) {
    $missingFields[] = 'Email';
}
if (empty($user['address1'])) {
    $missingFields[] = 'Address';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding-top: 80px;
        }

        #search-form {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .form-control {
            border: 1px solid #444;
            border-radius: 30px;
            padding: 15px;
            width: 400px;
            background-color: #1e1e1e;
            color: #e0e0e0;
            font-size: 16px;
            transition: all 0.3s ease;
            transform: scale(1);
        }

        .form-control::placeholder {
            color: white;
        }
        .form-control:focus::placeholder {
            color: black;
        }

        .form-control:focus {
            border-color: #303f9f;
            box-shadow: 0px 0px 8px rgba(48, 63, 159, 0.6);
            outline: none;
            transform: scale(1.05);
        }

        .btnprimary {
            background-color: #303f9f;
            border: none;
            border-radius: 20px;
            color: #e0e0e0;
            padding: 10px 20px;
            margin-left: 10px;
            cursor: pointer;
            transition: all 0.3s ease, transform 0.3s ease;
            transform: scale(1);
        }
        .btnprimary:hover,
        .btnprimary:focus {
            background-color: #1f2d7a;
            transform: scale(1.05);
        }

        .product-container {
            display: flex;
            flex-wrap: wrap; 
            justify-content: center;
            padding: 20px;
        }
        
        .pdt-card {
            margin: 15px;
            background: linear-gradient(135deg, #333333, #1a1a1a);
            color: #e0e0e0;
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
            transition: transform 0.3s;
        }

        .pdt-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(255, 255, 255, 0.2);
        }

        .pdt-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .name {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price {
            font-size: 22px;
            margin: 10px 0;
        }

        .price::after {
            content: " Rs";
            font-size: 14px;
        }

        .show-more-link {
            display: block;
            text-align: center;
            color: #303f9f;
            text-decoration: underline;
            cursor: pointer;
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

        .no-products {
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 10% auto;
            width: 80%;
        }

        .dflex {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btnsecondary {
            background-color: #303f9f;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.3s ease, transform 0.3s ease;
            transform: scale(1);
        }

        .btnsecondary:hover,
        .btnsecondary:focus {
            background-color: #1f2d7a;
            transform: scale(1.05);
        }
        a {
            color: #e0e0e0;
            text-decoration: none;
        }
        
        .profile-popup {
            background-color: #0066dd;
            width: 100x;
            position: fixed;
            top: 100px;
            right: 30px;
            color: #e0e0e0;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            animation: fadeInOut 3s forwards;
            z-index: 1000;
            display: inline-block;
        }

        .cart-popup {
            position: fixed;
            top: 100px;
            left: 30px;
            color: #e0e0e0;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            animation: fadeInOut 3s forwards;
            z-index: 1000;
            display: inline-block;
        }

        .cart-popup.added {
            background-color: #4caf50;
        }

        .cart-popup.updated {
            background-color: #2196f3;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-20px); }
        }

        @media (max-width: 600px) {
            .pdt-card {
                width: 80%;
                margin-bottom: 20px;
            }

            .form-control {
                width: 80%;
            }

            .product-container {
                padding: 10px;
            }
            .no-products {
                width: 80%;
            }
            .cart-popup {
                left: 10px;
                top: 50px;
            }
        }
    </style>
    <script>
        function addToCart(productId) {
            const missingFields = <?php echo json_encode($missingFields); ?>;
            if (missingFields.length > 0) {
                const message = "Complete your profile to add to cart. Missing: <br>" + missingFields.join(", ");
                const cartPopup = document.createElement('div');
                cartPopup.className = 'profile-popup';
                cartPopup.innerHTML = message;
                document.body.appendChild(cartPopup);
                
                setTimeout(() => {
                    cartPopup.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(cartPopup);
                    }, 300);
                }, 3000);
            } else {
                window.location.href = 'addcart.php?pid=' + productId;
            }
        }
    </script>
</head>
<body>

<div id="search-form">
    <form method="GET" action="search.php" style="display: flex; align-items: center;">
        <input class="form-control" type="text" name="search" placeholder="Search for products..." autocomplete="off">
        <button class="btnprimary" type="submit">Search</button>
    </form>
</div>

<?php if (isset($_SESSION['cart_message'])): ?>
    <div id="cart-popup" class="cart-popup <?php echo $_SESSION['cart_action'] === 'added' ? 'added' : 'updated'; ?>">
        <?php echo $_SESSION['cart_message']; ?>
    </div>
    <?php unset($_SESSION['cart_message']); ?>
<?php endif; ?>

<div class="product-container">
    <?php
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM product";
    if ($searchQuery) {
        $sql .= " WHERE name LIKE '%$searchQuery%'";
    }

    $sql_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sql_result) > 0) {
        while ($dbrow = mysqli_fetch_assoc($sql_result)) {
            $productName = urlencode($dbrow['name']);
            $sellerEmail = urlencode($dbrow['email']);

            echo "<div class='pdt-card'>
                    <div class='product-info'>
                        <div class='name'>{$dbrow['name']}</div>
                        <div class='price'>{$dbrow['price']}</div>
                        <img src='{$dbrow['impath']}' alt='Product Image'>
                        
                        <a href='product-cards.php?name=$productName&email=$sellerEmail' class='show-more-link'>Show More</a>
                    </div>";

            echo "<button class='btn-danger' onclick='addToCart({$dbrow['product_id']})'>Add to Cart</button>";

            echo "</div>";
        }
    } else {
        echo "<div class='no-products'>
                <h2>No products available in the market right now</h2>
                <p>Please check back later or try searching for something else.</p>
              </div>";
    }
    ?>
</div>

</body>
</html>
