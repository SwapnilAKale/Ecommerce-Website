<?php
session_start();
include "menu.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding-top: 80px;
        }
        .edit {
            margin: 2% auto;
            background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.5);
            padding: 20px;
            max-width: 520px;
            width: 90%;
        }
        form {
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile {
            margin-bottom: 20px;
            position: relative;
        }
        .profile label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .profile input {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #e0e0e0;
            border: 1px solid #555;
            border-radius: 5px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .profile input:focus {
            border-color: #303f9f;
            outline: none;
            transform: scale(1.03);
            box-shadow: 0px 6px 12px rgba(255, 255, 255, 0.2);
        }
        .profile input:focus + label {
            color: #03a9f4;
            transform: scale(1.05);
        }
        .buttons {
            text-align: center;
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
            transition: background-color 0.3s ease, transform 0.3s ease;
            transform: scale(1.03);
        }
        .buttons button:hover,
        .buttons button:focus {
            background-color: #b71c1c;
            transform: scale(1.05);
        }
        @media (max-width: 400px) {
            .edit, form {
                width: 90%;
            }
            .buttons button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="edit">
        <form action="updatedprofile.php" method="post" enctype="multipart/form-data">
            <h2>Update Your Profile</h2>
            <div class="profile">
                <label for="mobile">Mobile:</label>
                <input type="number" id="mobile" name="mobile" autocomplete="off" required oninput="if(this.value.length > 15) this.value = this.value.slice(0, 15);" pattern="\d+">
            </div>
            <div class="profile">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" autocomplete="off" required>
            </div>
            <div class="profile">
                <label for="address1">Address Line 1:</label>
                <input type="text" id="address1" name="address1" autocomplete="off" required>
            </div>
            <div class="profile">
                <label for="address2">Address Line 2:</label>
                <input type="text" id="address2" name="address2" autocomplete="off">
            </div>
            <div class="profile">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" autocomplete="off" required>
            </div>
            <div class="profile">
                <label for="image">Upload Image:</label>
                <input class="form-control" type="file" id="image" accept=".jpg,.png" name="image">
            </div>
            <input type="hidden" name="user_id">
            <div class="buttons">
                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
