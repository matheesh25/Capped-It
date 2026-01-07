<?php
session_start();
require 'connect.php';

$session_id = session_id();

$sql = "SELECT * FROM cart WHERE session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cart</title>
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

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .cart-table th, .cart-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .cart-table th {
            background-color: #0d08a7;
            color: white;
            font-weight: 600;
        }

        .cart-table tbody tr:hover {
            background-color: #f0f8ff;
        }

        .cart-table img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
        }

        .buy-now-button {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 18px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buy-now-button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .container {
                width: 95%;
                padding: 15px;
            }

            .cart-table th, .cart-table td {
                font-size: 14px;
                padding: 8px 10px;
            }

            .buy-now-button {
                width: 100%;
                padding: 15px 0;
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
        <a href="product.php">Products</a>
        <a href="cart.php" class="active">Cart</a>
        <a href="login.php">Login</a>
        <a href="terms and condition.html">Terms & Conditions</a>
    </nav>
    <div class="spacer"></div>
</header>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <form method="post" action="checkout.php">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price (Rs.)</th>
                        <th>Quantity</th>
                        <th>Total (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grand_total = 0;
                    while ($row = $result->fetch_assoc()):
                        $total = $row['price'] * $row['quantity'];
                        $grand_total += $total;
                        $product_img = "uploads/" . htmlspecialchars($row['image'] ?? 'product1.jpg');
                    ?>
                    <tr>
                        <td><input type="checkbox" name="selected_items[]" value="<?= $row['id'] ?>" checked></td>
                        <td><img src="<?= $product_img ?>" alt="Product Image"></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= number_format($total, 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="5" style="text-align:right; font-weight:bold;">Grand Total:</td>
                        <td style="font-weight:bold;">Rs.<?= number_format($grand_total, 2) ?></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="buy-now-button">Buy Now</button>
        </form>
    <?php else: ?>
        <p style="text-align:center; font-size:18px; margin-top:40px;">No items in the cart.</p>
    <?php endif; ?>
</div>

</body>
</html>
