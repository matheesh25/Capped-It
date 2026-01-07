<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// UPDATE
if (isset($_POST['update'])) {
    $id    = (int)$_POST['id'];
    $name  = $_POST['name'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$image");
        $conn->query("UPDATE products SET name='$name', description='$desc', price='$price', image='$image' WHERE id=$id");
    } else {
        $conn->query("UPDATE products SET name='$name', description='$desc', price='$price' WHERE id=$id");
    }
    header("Location: admin.php");
    exit();
}

//ADD
if (isset($_POST['add'])) {
    $name  = $_POST['name'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp   = $_FILES['image']['tmp_name'];
        $target = "uploads/$image";

        if (move_uploaded_file($tmp, $target)) {
            $insert = "INSERT INTO products (name, description, price, image) VALUES ('$name', '$desc', '$price', '$image')";
            if ($conn->query($insert)) {
                header("Location: admin.php");
                exit();
            } else {
                echo "Database error: " . $conn->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Image is required.";
    }
}

$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="icon" href="image5.jpg" type="image/jpg">
    <style>
        
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
            height: 100px;
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

        
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f2f2f2;
        }

        h2, h3 {
            color: #0d08a7;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        input[type="text"], input[type="number"], textarea {
            width: 96%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        button {
            background-color: #0d08a7;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #0059ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #0d08a7;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        img {
            border-radius: 8px;
        }

        a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: darkred;
        }
    </style>
</head>
<body>

    <header class="head">
        <div class="logo">
            <img src="image5.jpg" alt="Logo">
        </div>
        <nav class="nav">
                <a href="home.php">Home</a>
                <a href="product.php">Products</a>
                <a href="cart.php">Cart</a>
                <a href="login.php">Login</a>
                <a href="terms and condition.html">Terms & Conditions</a>
        </nav>
    </header>

    <h2>Admin Panel</h2>

    <h3>Add Product</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="text"     name="name"        placeholder="Product Name" required><br>
        <textarea              name="description" placeholder="Description"   required></textarea><br>
        <input type="number"   name="price"       placeholder="Price"         required><br>
        <input type="file"     name="image"                                    required><br>
        <button type="submit"  name="add">Add Product</button>
    </form>

    <h3>Manage Products</h3>
    <table>
        <tr>
            <th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Actions</th>
        </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form method="post" enctype="multipart/form-data">
                <td><img src="uploads/<?= $row['image'] ?>" width="100"></td>
                <td><input type="text"     name="name"        value="<?= $row['name'] ?>"></td>
                <td><textarea              name="description"><?= $row['description'] ?></textarea></td>
                <td><input type="number"   name="price"       value="<?= $row['price'] ?>"></td>
                <td>
                    <input type="file"     name="image"><br>
                    <input type="hidden"   name="id"          value="<?= $row['id'] ?>">
                    <button type="submit"  name="update">Update</button><br><br>
                    <a href="admin.php?delete=<?= $row['id'] ?>"
                       onclick="return confirm('Delete this item?')">Delete</a>
                </td>
            </form>
        </tr>
    <?php } ?>
    </table>
</body>
</html>
