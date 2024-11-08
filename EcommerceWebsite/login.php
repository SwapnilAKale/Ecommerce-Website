<?php
session_start();
$_SESSION["login_status"] = false;

$username = trim($_POST['username']);
$password = $_POST['password'];

include "connection.php";

$sql = "SELECT * FROM user WHERE BINARY username = '$username' AND BINARY password = '$password'";
$result = mysqli_query($conn, $sql);

if ($result && $result->num_rows > 0) {
    $dbrow = $result->fetch_assoc();

    $_SESSION["login_status"] = true;
    $_SESSION["username"] = $dbrow["username"];
    $_SESSION["userid"] = $dbrow["userid"];
    $_SESSION["usertype"] = $dbrow["usertype"];

    if ($dbrow["usertype"] == "Seller") {
        header("location: /seller/home.php");
    } else if ($dbrow["usertype"] == "Buyer") {
        header("location: /buyer/home.php");
    }
} else {
    header("location: /shared/login_failed.html");
}

$stmt->close();
$conn->close();
?>
