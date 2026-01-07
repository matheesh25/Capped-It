<?php
session_start();
require 'connect.php';

$paymentSuccess = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_number = $conn->real_escape_string($_POST['card_number']);
    $card_holder = $conn->real_escape_string($_POST['card_holder']);
    $expiry_date = $conn->real_escape_string($_POST['expiry_date']);
    $cvv = $conn->real_escape_string($_POST['cvv']);
    $session_id = session_id();

    $sql = "INSERT INTO payments (session_id, card_number, card_holder, expiry_date, cvv) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $session_id, $card_number, $card_holder, $expiry_date, $cvv);

    if ($stmt->execute()) {
        $paymentSuccess = true;
    } else {
        echo "<script>alert('Payment failed: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Method</title>
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
            border-radius: 10px;
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

        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
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
    <h2>Payment Details</h2>
    <form method="post" action="payment.php">
        <label>Card Number:</label>
        <input type="text" name="card_number" maxlength="16" required>

        <label>Card Holder Name:</label>
        <input type="text" name="card_holder" required>

        <label>Expiry Date (MM/YY):</label>
        <input type="text" name="expiry_date" maxlength="5" placeholder="MM/YY" required>

        <label>CVV:</label>
        <input type="text" name="cvv" maxlength="3" required>

        <button type="submit">Pay Now</button>
    </form>
</div>

<?php if ($paymentSuccess): ?>
<script>
    alert("Thanks for ordering from us!");
    window.location.href = "home.php";
</script>
<?php endif; ?>

</body>
</html>
