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
            color: #e0e0e0 !important;
            margin: 0;
            padding-top: 70px;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            padding: 0 0 5px 0;
            font-size: 32px;
            color: #e0e0e0;
        }
        .table-container {
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        .table th, .table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #333;
        }
        .table th {
            background-color: #303f9f;
            color: #ffffff;
        }
        .table tr:nth-child(even) {
            background-color: #333;
        }
        .table tr:hover {
            background-color: #444;
        }
        .table a {
            color: #ffa726;
            text-decoration: none;
        }
        .table a:hover {
            color: #ffcc80;
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

$sql = "SELECT * FROM orders WHERE Suser_id = $userId ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='no-orders'>
            <h1>No Orders Received Yet</h1>
            <p>It seems like you haven't received any orders yet. Once a buyer makes an order, you'll see it here!</p>
          </div>";
} 
else {
    echo "<div class='table-container'>
            <h1>Orders Received</h1>
            <table class='table'>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer Info</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                        <th>Current Status</th>
                        <th>Update Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";
                    while ($order = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>#{$order['order_id']}</td>
                                <td>{$order['Baddress1']}, {$order['Baddress2']}, {$order['Bcountry']}</td>
                                <td>{$order['total_amount']} Rs</td>
                                <td>{$order['order_date']}</td>
                                <td>{$order['status']}</td>
                                <td>
                                    <form action='update-status.php' method='POST'>
                                        <input type='hidden' name='order_id' value='{$order['order_id']}'>
                                        <select name='status'>
                                            <option value='Pending' " . ($order['status'] == 'Pending' ? 'selected' : 'disabled') . ">Pending</option>
                                            <option value='Shipped' " . ($order['status'] == 'Shipped' ? 'selected' : ($order['status'] == 'Pending' ? '' : 'disabled')) . ">Shipped</option>
                                            <option value='Delivered' " . ($order['status'] == 'Delivered' ? 'selected' : ($order['status'] == 'Shipped' ? '' : 'disabled')) . ">Delivered</option>
                                            <option value='Cancelled' " . ($order['status'] == 'Cancelled' ? 'selected' : ($order['status'] == 'Pending' ? '' : 'disabled')) . ">Cancelled</option>
                                        </select>
                                </td>
                                <td>
                                    <button type='submit'>Update Status</button>
                                    </form>
                                </td>

                            </tr>";
                    }
    echo "    </tbody>
            </table>
          </div>";
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