<?php
session_start();
include("connect.php");

if (isset($_FILES['profile_image']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $filename = $_FILES['profile_image']['name'];
    $tmpname = $_FILES['profile_image']['tmp_name'];
    $folder = "uploads/";

    if (!is_dir($folder)) mkdir($folder);

    $path = $folder . time() . "_" . basename($filename);
    if (move_uploaded_file($tmpname, $path)) {
        mysqli_query($conn, "UPDATE user SET profile_image='$path' WHERE email='$email'");
    }
}

header("Location: home.php");
exit();
