<?php
session_start();
include "../connection.php";
include "menu.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding-top: 80px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            margin-top: 20px;
        }
        .table-container {
            width: 90%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #4caf50;
            color: #ffffff;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .table a {
            color: #4caf50;
            text-decoration: none;
        }

        .table a:hover {
            text-decoration: underline;
        }

        .no-orders {
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
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            transform: scale(1);
        }

        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #b71c1c;
            transform: scale(1.05);
        }

        @media (max-width: 900px) {
            .table-container {
                width: 100%;
            }
            .table th, .table td {
                padding: 7px;
                font-size: 10px;
            }
            .btn-danger {
                font-size: 14px;
            }
            .no-orders {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<?php

$userId = $_SESSION['userid'];

$sql = "SELECT * FROM orders WHERE user_id = $userId ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='no-orders'>
            <h1>No Orders Yet</h1>
            <p>You haven't placed any orders yet. Start shopping and find your favorite products!</p>
            <a href='home.php'>
                <button class='btn-danger'>Browse Products</button>
            </a>
          </div>";
} else {
    echo "<div class='container'>
            <div class='table-container'>
                <h1>My Orders</h1>
                <table class='table'>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Quantity</th> <!-- New column for Quantity -->
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Delivery Address</th>
                            <th>Status</th>
                            <th>Seller Contact</th>
                        </tr>
                    </thead>
                    <tbody>";
                        while ($order = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>#{$order['order_id']}</td>
                                    <td>{$order['items']}</td>
                                    <td>{$order['quantity']}</td> <!-- Display the quantity -->
                                    <td>{$order['total_amount']} Rs</td>
                                    <td>{$order['order_date']}</td>
                                    <td>{$order['Baddress1']}, {$order['Baddress2']}, {$order['Bcountry']}</td>
                                    <td>{$order['status']}</td>
                                    <td>{$order['Smobile']}, {$order['Semail']}</td>
                                </tr>";
                        }

    echo "    </tbody>
            </table>
          </div>
          </div>";
}
?>
</body>
</html>
