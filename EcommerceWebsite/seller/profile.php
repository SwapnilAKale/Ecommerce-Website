<?php
include "authguard.php";
include '../connection.php';

$userid = $_SESSION['userid'];

$sql = "SELECT username, password, usertype, createddate, mobile, email, address1, address2, country, impath FROM user WHERE userid = $userid";
$sql_result = mysqli_query($conn, $sql);

if (!$sql_result) {
    die("Database query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($sql_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .profile-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .profilepic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 20px;
        }

        .profile-details {
            margin-bottom: 10px;
            text-align: left;
        }

        .profile-details strong {
            display: inline-block;
            min-width: 120px;
        }

        .btn-back {
            display: inline-block;
            width: 30%;
            margin: 10px 5px;
            background-color: #303f9f;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            transform: scale(1);
        }

        .btn-back:hover,
        .btn-back:focus {
            background-color: #1f2d7a;
            transform: scale(1.05);
        }

    </style>
</head>
<body>

<div class="profile-container">
    <h2 class="text-center">User Profile</h2>

    <?php if (!empty($user['impath'])): ?>
        <img src="<?php echo $user['impath']; ?>" alt="Profile Picture" class="profilepic">
    <?php endif; ?>

    <div class="profile-details">
        <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
    </div>
    <div class="profile-details">
        <strong>Password:</strong> <?php echo htmlspecialchars($user['password']); ?>
    </div>
    <div class="profile-details">
        <strong>User Type:</strong> <?php echo htmlspecialchars($user['usertype']); ?>
    </div>
    <div class="profile-details">
        <strong>Mobile Number:</strong> <?php echo htmlspecialchars($user['mobile']); ?>
    </div>
    <div class="profile-details">
        <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
    </div>
    <div class="profile-details">
        <strong>Address 1:</strong> <?php echo htmlspecialchars($user['address1']); ?>
    </div>
    <div class="profile-details">
        <strong>Address 2:</strong> <?php echo htmlspecialchars($user['address2']); ?>
    </div>
    <div class="profile-details">
        <strong>Country:</strong> <?php echo htmlspecialchars($user['country']); ?>
    </div>
    <div class="profile-details">
        <strong>Created Date:</strong> <?php echo htmlspecialchars($user['createddate']); ?>
    </div>

    <a href="home.php" class="btn-back">Back to Home</a>
    <a href="deleteaccount.php" onclick="return confirmDelete()" class="btn-back">Delete Account</a>
    <a href="../shared/logout.php" class="btn-back">Logout</a>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete your account? This action cannot be undone.");
    }
</script>

</body>
</html>
