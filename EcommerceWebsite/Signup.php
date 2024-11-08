<?php

include "connection.php";

$username = trim($_POST['username']);
$password = $_POST['password'];
$usertype = $_POST['usertype'];

$search = mysqli_query($conn, "SELECT * FROM user WHERE BINARY username = '$username'");

if ($search->num_rows > 0) {
    $error_message = "Username already exists. Please choose a different one.";
    echo "<script>
        window.location.href = '/shared/Signup.html?error=$error_message&username=$username';
    </script>";
} else {
    $insert = mysqli_query($conn, "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')");

    if ($insert) {
        echo "Signup successful!";
        header("Location: /shared/login.html");
        exit();
    } else {
        echo "Error during signup: " . mysqli_error($conn);
    }
}
?>
