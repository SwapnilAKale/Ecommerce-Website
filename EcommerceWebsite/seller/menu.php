<?php
include "../connection.php";

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];

$sql = "SELECT username, impath FROM user WHERE userid = $userid";
$sql_result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($sql_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            padding-top: 0;
            transition: padding-top 0.5s ease;
        }

        .navbar {
            background-color: #1a1a1a;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .btn-primary {
            background-color: #303f9f;
            border: none;
            transition: all 0.3s ease, transform 0.3s ease;
            transform: scale(1);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #1f2d7a;
            transform: scale(1.05);
        }

        .menubuttons {
            color: #e0e0e0;
        }

        .navbar .d-flex > a {
            margin-right: 15px; 
        }

        .navbar .d-flex > a:last-child {
            margin-right: 0; 
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        @media (max-width: 700px) {
            .btn {
                font-size: 12px;
                width: 150px;
                padding: 5px 7px;
            }

            .navbar-collapse {
                justify-content: flex-start;
                width: 100%;
            }

            .navbar .d-flex {
                flex-direction: column;
                align-items: flex-start;
                margin-top: 10px;
            }

            .navbar .d-flex > a {
                margin-right: 0; 
                margin-bottom: 5px;
            }
        }

        .navbar .d-flex.align-items-center {  
            position: absolute;  
            top: 15px; 
            right: 10px; 
        }

        .navbar-toggler {
            background-color: white;
            border: none;
        }

        .navbar-toggler:focus {
            outline: none;
        }

        .navbar-toggler-icon {
            background-color: white;
            width: 30px;
            height: 30px;
        }


        @media (max-width: 700px) {
            .navbar-toggler {
                padding: 5px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg p-3 fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="d-flex me-auto">
                <a href="/seller/home.php"><button class="btn btn-primary menubuttons">Launch Products</button></a>
                <a href="/seller/view.php"><button class="btn btn-primary menubuttons">View Your Products</button></a>
                <a href="/seller/searchbar.php"><button class="btn btn-primary menubuttons">Search Other Products</button></a>
                <a href="/seller/vieworders.php" class="btn btn-primary menubuttons">View Orders</a>
                <a href="/seller/profileedit.php"><button class="btn btn-primary menubuttons">Edit Profile</button></a>
            </div>
        </div>

        <div class="d-flex align-items-center ms-auto">
            <?php if (!empty($user['impath'])): ?>
                <a href="profile.php">
                    <img src="<?php echo $user['impath']; ?>" alt="Profile Picture" class="profile-pic">
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
