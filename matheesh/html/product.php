<?php
include 'connect.php'; 

// Fetch all products from database
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products</title>
    <link rel="icon" type="image/jpg" href="image5.jpg" />
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
        }

        .head {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            background: #0d08a7;
            padding: 10px 20px;
            border-bottom: 1px solid white;
        }

        .logo {
            grid-column: 1;
        }

        .logo img {
            height: 120px;
            width: auto;
        }

        .nav {
            grid-column: 2;
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
            color: #000000;
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        .nav a.active {
            border-bottom: 2px solid black;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }

        .product-item {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
        }

        .product-item:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .product-item img {
            max-width: 100%;
            height: 150px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-item h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #0d08a7;
        }

        .product-item p {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .add-to-cart {
            padding: 8px 15px;
            font-size: 14px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: darkblue;
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

            .product-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                padding: 10px;
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

    </style>
</head>
<body>
    <header class="head">
        <div class="logo">
            <img src="image5.jpg" alt="Logo" />
        </div>
        <nav class="nav">
            <a href="home.php">Home</a>
            <a href="product.php" class="active">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="terms and condition.html">Terms & Conditions</a>
        </nav>
        <div class="spacer"></div>
    </header>

    <div class="product-grid">
        <?php while ($row = $result->fetch_assoc()) { 
            $id = $row['id'];
            $name = htmlspecialchars($row['name']);
            $price = $row['price'];
            $image = htmlspecialchars($row['image']); // filename stored in DB
            $nameUrl = urlencode($row['name']);
        ?>
        <div class="product-item">
            <a href="product-detail.php?id=<?= $id ?>&name=<?= $nameUrl ?>&price=<?= $price ?>">
                <img src="uploads/<?= $image ?>" alt="<?= $name ?>" />
                <h3><?= $name ?></h3>
            </a>
            <p>Rs.<?= $price ?></p>
            <button class="add-to-cart" 
                    data-id="<?= $id ?>" 
                    data-name="<?= $name ?>" 
                    data-price="<?= $price ?>">
                Add to Cart
            </button>
        </div>
        <?php } ?>
    </div>

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
                <p><a href="home.php">Home</a></p>
                <p><a href="product.php">Products</a></p>
                <p><a href="cart.php">Cart</a></p>
                <p><a href="login.php">Login</a></p>
                <p><a href="register.html">Register</a></p>
                <p><a href="terms and condition.html">Terms & Conditions</a></p>
            </div>
            <div class="footer-section">
                <h3>Operating Hours</h3>
                <p>Monday - Friday: 9 AM - 8 PM</p>
                <p>Saturday: 10 AM - 6 PM</p>
                <p>Sunday: Closed</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <p>
                    <a href="https://facebook.com" target="_blank">Facebook</a>  
                    <a href="https://twitter.com" target="_blank">Twitter</a> 
                    <a href="https://tiktok.com" target="_blank">TikTok</a>
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                const price = this.dataset.price;

                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${productId}&name=${encodeURIComponent(productName)}&price=${price}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Added to cart!");
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(error => {
                    alert("Failed to add to cart.");
                    console.error(error);
                });
            });
        });
    </script>
</body>
</html>
