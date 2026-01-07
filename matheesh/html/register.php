<?php
include 'connect.php';
session_start();

if (isset($_POST['signUp'])) {
    $userName = $_POST['uName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $checkEmail = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email Address Already Exists!'); window.location.href='login.php';</script>";
    } else {
        $insertQuery = "INSERT INTO users(userName, email, password) VALUES ('$userName', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php?showLogin=true';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: home.php");
        exit();
    } else {
        echo "<script>alert('Incorrect Email or Password'); window.location.href='login.php?showLogin=true';</script>";
    }
}
?>
