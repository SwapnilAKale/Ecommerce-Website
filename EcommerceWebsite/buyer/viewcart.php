<?php
include "authguard.php";
include "menu.php";
include "../connection.php";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Cart</title>
        <style>
            body {
                background-color: #121212;
                color: #e0e0e0;
                margin: 0;
                padding-top: 80px;
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
            .total {
                font-size: 30px;
                font-weight: bold;
                margin-top: 10px;
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

            .quantity {
                font-size: 18px;
                margin: 5px 0;
            }

            .show-more-link {
                display: block;
                text-align: center;
                color: #303f9f;
                text-decoration: underline;
                cursor: pointer;
            }

            .no-products {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                width: 100%;
                height: 90vh;
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
            .btndanger {
                background-color: #d32f2f;
                border: none;
                color: #e0e0e0;
                border-radius: 8px;
                padding: 12px 20px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease, transform 0.3s ease;
                transform: scale(1);
            }

            .btndanger:hover,
            .btndanger:focus {
                background-color: #b71c1c;
                transform: scale(1.05);
            }
            .btn-success {
                border: none;
                color: #e0e0e0;
                border-radius: 8px;
                padding: 12px 20px;
                cursor: pointer;
                font-size: 16px;
                transition: all 0.3s ease, transform 0.3s ease;
                transform: scale(1);
            }

            .btn-success:hover,
            .btn-success:focus {
                transform: scale(1.05);
            }

            .danger {
                background-color: #d32f2f;
                border: none;
                color: #e0e0e0;
                border-radius: 8px;
                padding: 5px 14px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease, transform 0.3s ease;
                transform: scale(1);
            }

            .danger:hover,
            .danger:focus {
                background-color: #b71c1c;
                transform: scale(1.05);
            }
            .success {
                background-color: #56ae57;
                border: none;
                color: #e0e0e0;
                border-radius: 8px;
                padding: 5px 14px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease, transform 0.3s ease;
                transform: scale(1);
            }

            .success:hover,
            .success:focus {
                background-color: #3e8e41;
                transform: scale(1.05);
            }
            
            .display {
                margin-left: 20px;
            }
            
            .order-popup {
                position: fixed;
                top: 100px;
                left: 190px;
                background-color: #303f9f;
                color: #e0e0e0;
                padding: 10px 20px;
                border-radius: 5px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
                animation: fadeInOut 3s forwards;
                z-index: 1000;
                display: inline-block;
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
                    margin: 10px 0;
                }

                .btn-danger {
                    font-size: 14px;
                }
            }
        </style>
    </head>
</html>

<?php if (isset($_SESSION['order_message'])): ?>
    <div class="order-popup">
        <div class="order-popup-triangle"></div>
        <?php echo $_SESSION['order_message']; ?>
    </div>
    <?php unset($_SESSION['order_message']); ?>
<?php endif; ?>


<?php
$sql_result = mysqli_query($conn, "SELECT cart.quantity, cart.product_id, product.* FROM cart JOIN product ON cart.product_id = product.product_id WHERE cart.userid = $_SESSION[userid]");

$total = 0;

if ($sql_result->num_rows === 0) {
    echo "
    <div class='no-products'>
        <h1>Your Cart is Empty</h1>
        <p>Looks like you haven't added anything to your cart yet.</p>
        <a href='home.php'>
            <button class='btndanger'>Continue Shopping</button>
        </a>
    </div>";
} else {
    echo "<div class='product-container'>";
    while ($dbrow = mysqli_fetch_assoc($sql_result)) {
        $productName = urlencode($dbrow['name']);
        $sellerEmail = urlencode($dbrow['email']);
        $address_details = "{$dbrow['address1']}, {$dbrow['address2']}";
        $productId = $dbrow['product_id'];
        $Owner = $dbrow['owner'];
    
        echo "<div class='pdt-card'>
                    <div class='product-info'>
                        <div class='name'>{$dbrow['name']}</div>
                        <div class='price'>{$dbrow['price']}</div>
                        <div class='quantity'>
                            Quantity: {$dbrow['quantity']}
                            <form action='updatequantity.php' method='POST' style='display:inline-block;'>
                                <input type='hidden' name='product_id' value='{$productId}'>
                                <button type='submit' name='action' value='decrement' class='danger'>-</button>
                                <button type='submit' name='action' value='increment' class='success'>+</button>
                            </form>
                        </div>
                        <img src='{$dbrow['impath']}' alt='Product Image'>
    
                        <a href='product-cards-cart.php?name=$productName&email=$sellerEmail' class='show-more-link'>Show More</a>
    
                        <form action='deletecart.php' method='get'>
                            <input type='hidden' name='cartid' value='{$dbrow['product_id']}'>
                            <button class='btn-danger'>Remove from Cart</button>
                        </form>
                    </div>
              </div>";
        $total += $dbrow['price'] * $dbrow['quantity'];
    }
    
    
    echo "</div>";

    echo "<div class='display'>
            <div class='total'>Place Order: $total Rs</div>
            <a href='checkout.php'>
                <button class='btn btn-success'>BUY</button>
            </a>
        </div>";
}
?>