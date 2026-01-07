<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("connect.php");

// Search functionality
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        header("Location: product_detail.php?id=" . $product['id'] . 
               "&name=" . urlencode($product['name']) . 
               "&price=" . $product['price']);
        exit();
    } else {
        $noResults = true;
    }
}

// Fetch user data if logged in
$userData = [];
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Capped It</title>
    <link rel="icon" type="image/jpg" href="image5.jpg">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .head {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            background: #0d08a7;
            padding: 10px 20px;
            border-bottom: 1px solid white;
            position: relative;
        }

        .logo img {
            height: 120px;
            width: auto;
        }

        .nav {
            display: flex;
            justify-content: center;
            gap: 25px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }

        .nav a:hover {
            background-color: #00ccff;
            color: #000;
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        .nav a.active {
            border-bottom: 2px solid black;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            max-width: 700px;
            width: 90%;
        }

        .search-container form {
            display: flex;
            width: 100%;
        }

        #search-input {
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            width: 100%;
            outline: none;
        }

        button {
            padding: 10px 16px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: darkblue;
        }

        .slideshow-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            flex-wrap: wrap;
        }

        .slide {
            width: 23%;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
            transition: transform 0.3s;
        }

        .slide img {
            width: 100%;
            height: auto;
        }

        .slide:hover {
            transform: scale(1.02);
        }

        footer {
            background: #000;
            color: white;
            text-align: center;
            padding: 20px 10px;
            width: 100%;
            margin-top: auto;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            background-color: #222;
            color: white;
            padding: 30px 20px;
            flex-wrap: wrap;
        }

        .footer-section {
            width: 22%;
            min-width: 200px;
            margin-bottom: 20px;
        }

        .footer-section h3 {
            border-bottom: 2px solid #00ccff;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .footer-section a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 5px;
        }

        .footer-section a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .slideshow-container {
                flex-direction: column;
                align-items: center;
            }

            .slide {
                width: 90%;
            }

            .footer-container {
                flex-direction: column;
                align-items: center;
            }

            .footer-section {
                width: 100%;
                text-align: center;
            }
        }

        .profile-container {
            position: absolute;
            right: 20px;
            top: 25px;
            z-index: 999;
        }

        .profile-icon {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            cursor: pointer;
            object-fit: cover;
            border: 2px solid white;
        }

        .profile-popup {
            position: absolute;
            top: 60px;
            right: 0;
            width: 260px;
            background-color: white;
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
            border-radius: 10px;
            padding: 15px;
            display: none;
        }

        .profile-popup p {
            font-size: 14px;
            margin: 8px 0;
        }

        .profile-pic-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: block;
            object-fit: cover;
            cursor: pointer;
        }

        .profile-popup button {
            background-color: #007BFF;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        .profile-popup .signout-btn {
            background-color: #dc3545;
        }

        .profile-popup .signout-btn:hover {
            background-color: #c82333;
        }

        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
    <header class="head">
        <div class="logo">
            <img src="image5.jpg" alt="Logo">
        </div>
        <nav class="nav">
            <a href="home.php" class="active">Home</a>
            <a href="product.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="terms and condition.html">Terms & Conditions</a>
        </nav>

        <?php if (!empty($userData)) : ?>
        <div class="profile-container">
            <img src="<?= htmlspecialchars($userData['profile_image'] ?? 'default-profile.png') ?>" class="profile-icon" onclick="toggleProfile()" alt="Profile">
            <div class="profile-popup" id="profilePopup">
                <form id="uploadForm" action="upload_profile.php" method="POST" enctype="multipart/form-data">
                    <label for="profileInput">
                        <img src="<?= htmlspecialchars($userData['profile_image'] ?? 'default-profile.png') ?>" class="profile-pic-large" id="profilePreview">
                    </label>
                    <input type="file" name="profile_image" id="profileInput" accept="image/*" onchange="document.getElementById('uploadForm').submit();">
                </form>
                <p><strong>Name:</strong> <?= htmlspecialchars($userData['userName']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($userData['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($userData['phone'] ?? 'Not set') ?></p>
                <form action="logout.php" method="POST">
                    <button type="submit" class="signout-btn">Sign Out</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </header>

    <div class="search-container">
        <form method="GET" action="home.php">
            <input type="text" name="search" id="search-input" placeholder="Search products..." required>
            <button type="submit">Search</button>
        </form>
    </div>

    <main>
        <section class="slideshow-container">
            <div class="slide"><img src="image1.jpg" alt="Shop Now 1"></div>
            <div class="slide"><img src="image2.jpg" alt="Shop Now 2"></div>
            <div class="slide"><img src="image3.jpg" alt="Shop Now 3"></div>
            <div class="slide"><img src="image4.jpg" alt="Shop Now 4"></div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: cappedit@gmail.com</p>
                <p>Phone: 075-022-7589</p>
                <p>Address: 144/2, Pattiwila, Gonawala, Kelaniya</p>
            </div>
            <div class="footer-section">
                <h3>Get Help</h3>
                <a href="home.php">Home</a>
                <a href="product.php">Products</a>
                <a href="cart.php">Cart</a>
                <a href="login.php">Login</a>
                <a href="register.html">Register</a>
                <a href="terms and condition.html">Terms & Conditions</a>
            </div>
            <div class="footer-section">
                <h3>Operating Hours</h3>
                <p>Mon - Fri: 9 AM - 8 PM</p>
                <p>Saturday: 10 AM - 6 PM</p>
                <p>Sunday: Closed</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <a href="https://facebook.com" target="_blank">Facebook</a>
                <a href="https://twitter.com" target="_blank">Twitter</a>
                <a href="https://tiktok.com" target="_blank">TikTok</a>
            </div>
        </div>
    </footer>

    <script>
    function toggleProfile() {
        const popup = document.getElementById("profilePopup");
        popup.style.display = popup.style.display === "block" ? "none" : "block";
    }

    window.addEventListener("click", function(e) {
        const popup = document.getElementById("profilePopup");
        const icon = document.querySelector(".profile-icon");
        if (popup && icon && !popup.contains(e.target) && !icon.contains(e.target)) {
            popup.style.display = "none";
        }
    });
    </script>
</body>
</html>
