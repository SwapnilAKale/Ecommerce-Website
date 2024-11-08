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

$profileIncomplete = !empty($missingFields);
$missingFieldsJson = json_encode($missingFields);
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
            padding-top: 70px;
        }
        #container {
            margin: 5% auto;
            background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.5);
            padding: 20px;
            max-width: 600px;
            width: 90%;
        }
        .form-control {
            background-color: #1a1a1a;
            color: #e0e0e0;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        .form-control:focus + label {
            color: #03a9f4;
            transform: scale(1.05);
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
        .text-center {
            text-align: center;
        }
        .dflex {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            transition: transform 0.3s ease, color 0.3s ease;
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
        .show-popup {
            display: inline-block;
            animation: fadeInOut 4s forwards;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px) scale(0.95); }
            20% { opacity: 1; transform: translateY(0) scale(1); }
            80% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-20px) scale(0.95); }
        }

        @media (max-width: 600px) {
            #container {
                width: 90%;
                padding: 15px; 
            }

            .dflex {
                width: 90%; 
                margin: 20px auto;
                display: flex; 
                flex-direction: column;
                align-items: center;
            }

            .form-control {
                width: 90%; 
                margin: 10px auto; 
            }

            .btn-danger,
            .btnsecondary {
                padding: 10px 20px; 
                width: 90%; 
                margin: 10px auto; 
            }
        }

    </style>
</head>
<body>
    <div class="dflex">
        <form action="upload.php" id="container" class="w-100" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Product Name" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="price">Product Price:</label>
                <input class="form-control" type="number" id="price" name="price" placeholder="Product Price" oninput="if(this.value.length > 15) this.value = this.value.slice(0, 15);" pattern="\d+" required>
            </div>
            <div class="form-group">
                <label for="detail">Product Description:</label>
                <textarea class="form-control" id="detail" name="detail" placeholder="Product Description" cols="30" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input class="form-control" type="file" id="image" accept=".jpg,.png" name="image" required>
            </div>
            <div class="text-center">
                <button id="addProductBtn" class="btn-danger <?php echo $profileIncomplete ? 'btn-disabled' : ''; ?>"
                <?php echo $profileIncomplete ? 'disabled title="Complete your profile to add products"' : ''; ?>>
                Add Product</button>
            </div>
        </form>
    </div>

    <div id="profile-popup" class="profile-popup">
        <p>Please update your profile to initiate your business.<br>Missing: <?php echo implode(", ", $missingFields); ?></p>
        <a href="profileedit.php">
            <button class="btn-danger" style="margin-top: 10px;">Update Profile</button>
        </a>
    </div>

    <script>
        const missingFields = <?php echo $missingFieldsJson; ?>;
        const form = document.querySelector('form');
        const popup = document.getElementById('profile-popup');

        // Show the popup if there are missing fields
        if (missingFields.length > 0) {
            popup.classList.add('show-popup');
            setTimeout(() => {
                popup.classList.remove('show-popup');
            }, 3000);  // Popup display duration
        }
    </script>

</body>
</html>