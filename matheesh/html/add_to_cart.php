<?php
session_start();
require 'connect.php';

$session_id = session_id();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['id'];
    $product_name = $_POST['name'];
    $price = $_POST['price'];

    // Check if product already in cart
    $check_sql = "SELECT * FROM cart WHERE product_id = ? AND session_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $product_id, $session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND session_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $product_id, $session_id);
        $update_stmt->execute();
    } else {
        // Insert new product
        $insert_sql = "INSERT INTO cart (product_id, product_name, price, quantity, session_id)
                       VALUES (?, ?, ?, '1', ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("isds", $product_id, $product_name, $price, $session_id);
        $insert_stmt->execute();
    }

    echo "success";
} else {
    echo "Invalid request.";
}
?>
