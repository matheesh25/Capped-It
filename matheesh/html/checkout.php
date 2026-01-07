<?php
session_start();
require 'connect.php';
$session_id = session_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $sql = "INSERT INTO customers (session_id, name, email, address, phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $session_id, $name, $email, $address, $phone);

    if ($stmt->execute()) {
        header("Location: payment.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="icon" type="image/jpg" href="image5.jpg">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f3f7;
        }


        header {
            width: 100%;
            padding: 20px 0;
            background: #0d08a7;
            border-bottom: 1px solid white;
            text-align: center;
        }

        .logo img {
            width: 200px;
            height: 200px;
            border-radius: 20px;
        }


        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            border-radius: 12px;
        }

        .form-container h2 {
            text-align: center;
            color: #0d08a7;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            margin-top: 15px;
            font-weight: bold;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        form textarea {
            resize: vertical;
            height: 80px;
        }

        form button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background-color: #0d08a7;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #090470;
        }

        @media (max-width: 600px) {
            .form-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="image5.jpg" alt="Logo">
    </div>
</header>

<div class="form-container">
    <h2>Checkout - Customer Details</h2>
    <form method="post" action="checkout.php">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Address:</label>
        <textarea name="address" required></textarea>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <button type="submit">Proceed to Payment</button>
    </form>
</div>

</body>
</html>
