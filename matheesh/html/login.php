<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connect.php';

$message = "";
$showLogin = true; // default view is login form

//REGISTER
if (isset($_POST['signUp'])) {
    $userName = $_POST['uName'];
    $email    = $_POST['email'];
    $password = md5($_POST['password']);

    $checkEmail = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $message = "Email Address Already Exists!";
        $showLogin = false;
    } else {
        $insert = "INSERT INTO user (userName, email, password)
                   VALUES ('$userName', '$email', '$password')";
        if ($conn->query($insert) === TRUE) {
            header("Location: login.php?showLogin=true");
            exit();
        } else {
            $message = "Error: " . $conn->error;
            $showLogin = false;
        }
    }
}

/* ----------  LOGIN  ---------- */
if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $rawPassword = $_POST['password'];
    $hashedPassword = md5($rawPassword);

    // Check if user exists
    $sql = "SELECT * FROM user WHERE email='$email' AND password='$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;

        // ✅ Admin redirect
        if ($email === 'admin@gmail.com' && $rawPassword === 'admin@123') {
            header("Location: admin.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        $message = "Incorrect Email or Password";
        $showLogin = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login & Register</title>
    <link rel="icon" type="image/jpg" href="image5.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 40px;
        }

        /* === HEADER === */
        header {
            background-color: #0d08a7;
            border-bottom: 1px solid white;
            width: 100%;
            padding: 10px 0;
        }
        .container-header {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            padding: 0 20px;
        }

        .logo img {
            height: 120px;
            width: auto;
            display: block;
        }

        nav.nav {
            justify-self: center;
            display: flex;
            gap: 25px;
        }
        nav.nav a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }
        nav.nav a:hover {
            background-color: #00ccff;
            color: #000;
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }
        nav.nav a.active {
            border-bottom: 2px solid black;
        }
        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 40px 35px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d08a7;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #00ccff;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 15px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Social icons */
        .social-icons {
            text-align: center;
            margin-bottom: 15px;
        }
        .social-icons a {
            font-size: 30px;
            margin: 0 15px;
            transition: transform 0.2s;
            color: inherit;
        }
        .social-icons a:hover {
            transform: scale(1.3);
        }

        .message {
            color: red;
            margin: 10px 0 20px 0;
            text-align: center;
            font-weight: 600;
        }

        span {
            display: block;
            text-align: center;
            font-size: 15px;
        }

        span a {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        @media (max-width: 480px) {
            .container {
                max-width: 90%;
                padding: 25px 20px;
            }
            .container-header {
                grid-template-columns: 1fr auto 1fr;
                padding: 0 15px;
            }
            nav.nav {
                gap: 15px;
                font-size: 18px;
            }
            .logo img {
                height: 90px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container-header">
        <div class="logo"><img src="image5.jpg" alt="Logo"></div>
        <nav class="nav">
            <a href="home.php">Home</a>
            <a href="product.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="terms and condition.html">Terms & Conditions</a>
        </nav>
        <div></div>
    </div>
</header>

<div class="container">

    <form method="post" id="loginForm" <?php if (!$showLogin) echo 'style="display:none;"'; ?>>
        <h2>Login</h2>
        <div class="message"><?php if (isset($_POST['signin'])) echo $message; ?></div>
        <input type="text" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="signin">Login</button><br><br>
        <div class="social-icons">
            <a href="#"><i class="fab fa-google" style="color:#db4a39;"></i></a>
            <a href="#"><i class="fab fa-facebook" style="color:#3b5998;"></i></a>
        </div><br>
        <span>Don't have an account yet?
              <a href="#" onclick="showRegister()" style="color:blue;text-decoration:underline;">Sign Up</a></span>
    </form>


    <form method="post" id="registerForm" <?php if ($showLogin) echo 'style="display:none;"'; ?>>
        <h2>Register</h2>
        <div class="message"><?php if (isset($_POST['signUp'])) echo $message; ?></div>
        <input type="text" name="uName" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="signUp">Register</button><br><br>
        <div class="social-icons">
            <a href="#"><i class="fab fa-google" style="color:#db4a39;"></i></a>
            <a href="#"><i class="fab fa-facebook" style="color:#3b5998;"></i></a>
        </div><br>
        <span>Already have an account?
              <a href="#" onclick="showLogin()" style="color:blue;text-decoration:underline;">Sign In</a></span>
    </form>
</div>

<script>
function showLogin(){
    document.getElementById('loginForm').style.display='block';
    document.getElementById('registerForm').style.display='none';
}
function showRegister(){
    document.getElementById('loginForm').style.display='none';
    document.getElementById('registerForm').style.display='block';
}
</script>
</body>
</html>
