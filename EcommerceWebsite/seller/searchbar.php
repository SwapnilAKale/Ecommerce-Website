<?php
include "authguard.php";
include "menu.php";
include "../connection.php";

$currentUserId = $_SESSION['userid']; 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding-top: 70px;
        }

        #search-form {
            padding: 30px 0;
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
            transition: all 0.3s ease;
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

        .error-message {
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 10% auto;
            width: 80%;
        }
    </style>
</head>
<body>

<div id="search-form">
    <form method="GET" action="search.php" style="display: flex; align-items: center;">
        <input class="form-control" type="text" name="search" placeholder="Search for products..." autocomplete="off">
        <button class="btnprimary" type="submit">Search</button>
    </form>
</div>

<?php
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

$sql = "SELECT product.*, user.mobile, user.email, user.address1, user.address2, user.country 
        FROM product 
        JOIN user ON product.owner = user.userid 
        WHERE product.owner != '$currentUserId'";

if ($searchQuery) {
    $sql .= " AND product.name LIKE '%$searchQuery%'";
}

$sql_result = mysqli_query($conn, $sql);

if (mysqli_num_rows($sql_result) == 0) {
    echo "<div class='error-message'>
            <h1>No Products Found</h1>
            <p>It seems there are currently no products available from other sellers.</p>
            <p>Check back later or try searching for something else!</p>
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
            </div>";
    }
    echo "</div>";
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbarToggler = document.querySelector(".navbar-toggler");
        const navbar = document.querySelector(".navbar");
        const body = document.body;

        let isExpanded = false;

        navbarToggler.addEventListener("click", function() {
            isExpanded = !isExpanded;

            if (isExpanded) {
                body.style.transition = "padding-top 0.3s ease";
                body.style.paddingTop = "250px";
            } else {
                body.style.transition = "padding-top 0.3s ease";
                body.style.paddingTop = "70px";
            }
        });
    });
</script>

</body>
</html>