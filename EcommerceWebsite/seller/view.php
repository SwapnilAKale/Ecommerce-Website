<?php
include "authguard.php";
include "menu.php";
include "../connection.php";

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

$missingFieldsJson = json_encode($missingFields);
?>

<!DOCTYPE html>
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
            padding-top: 70px;
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
        .popup {
            display: none;
            position: fixed;
            top: 100px;
            right: 30px;
            background-color: #f44336;
            color: white;
            padding: 16px;
            z-index: 1000;
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.6s ease-in-out, transform 0.6s ease-in-out;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            20% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            80% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
        }

        .show-popup {
            display: inline-block;
            animation: fadeInOut 4s forwards;
        }
    </style>
</head>
<body>

<div id="profile-popup" class="popup">
    <p>Please update your profile to initiate your business.<br>Missing: <?php echo implode(", ", $missingFields); ?></p>
    <a href="profileedit.php">
        <button class="btn-danger" style="margin-top: 10px;">Update Profile</button>
    </a>
</div>

<?php
$sql_result = mysqli_query($conn, "SELECT * FROM product WHERE owner=$_SESSION[userid]");

if ($sql_result->num_rows === 0) {
    echo "<div class='no-products'>
            <h1>No Products Launched</h1>
            <p>It looks like you haven't added any products yet. Start by adding some to your inventory!</p>
            <a id='launch-product-link' href='home.php'>
                <button class='btn-danger' id='launch-product-btn'>Launch Product</button>
            </a>
          </div>";
} else {
    echo "<div class='product-container'>";
    while ($dbrow = mysqli_fetch_assoc($sql_result)) {
        $productName = urlencode($dbrow['name']);
        $sellerEmail = urlencode($dbrow['email']);

        echo "<div class='pdt-card'>
                <div class='product-info'>
                    <div class='name'>{$dbrow['name']}</div>
                    <div class='price'>{$dbrow['price']}</div>
                    <img src='{$dbrow['impath']}' alt='Product Image'>
                </div>
                <a href='product-cards-cart.php?name=$productName&email=$sellerEmail' class='show-more-link'>Show More</a>
                
                <form action='edit.php' method='post'>
                    <input type='hidden' name='product_id' value='{$dbrow['product_id']}' />
                    <button class='btn-danger' type='submit'>Edit</button>
                </form>
                <form action='delete.php' method='post'>
                    <input type='hidden' name='product_id' value='{$dbrow['product_id']}' />
                    <button class='btn-danger' type='submit'>Delete</button>
                </form>
            </div>";
    }
    echo "</div>";
}
?>

<script>
    const missingFields = <?php echo $missingFieldsJson; ?>;
    const popup = document.getElementById('profile-popup');
    const launchProductButton = document.getElementById('launch-product-btn');

    // Check if there are missing fields
    if (missingFields.length > 0) {
        // Prevent product launch if the profile is incomplete
        launchProductButton.addEventListener('click', (e) => {
            e.preventDefault();  // Prevent redirection

            // Show the popup
            showPopup();
        });
    }

    // Function to show the popup
    function showPopup() {
        popup.classList.add('show-popup');
        setTimeout(() => {
            popup.classList.remove('show-popup');
        }, 3000);  // Popup display duration
    }
</script>

</body>
</html>
