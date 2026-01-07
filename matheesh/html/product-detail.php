<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Product Detail</title>
  <style>

    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f6fa;
      margin: 0;
      padding: 0;
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      background: #0d08a7;
      padding: 10px 20px;
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      border-bottom: 1px solid white;
    }
    .logo {
      grid-column: 1;
    }
    .logo img {
      height: 100px;
      width: auto;
    }
    nav.nav {
      grid-column: 2;
      display: flex;
      justify-content: center;
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
      box-shadow: 0 6px 12px rgba(0,0,0,0.3);
      border-radius: 8px;
    }
    nav.nav a.active {
      border-bottom: 2px solid black;
    }
    .container {
      max-width: 900px;
      margin: 30px auto;
      padding: 0 20px 40px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h1#productName {
      margin-top: 0;
      font-size: 2.5rem;
      text-align: center;
      padding-top: 20px;
    }
    .product-image {
      display: block;
      max-width: 100%;
      height: auto;
      margin: 20px auto;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    p.price {
      font-size: 1.8rem;
      font-weight: bold;
      text-align: center;
      margin: 20px 0;
      color: #0d08a7;
    }
    #addToCartBtn, #buyNowBtn {
      cursor: pointer;
      background-color: #007BFF;
      border: none;
      color: white;
      font-size: 1.1rem;
      padding: 12px 30px;
      margin: 10px 15px;
      border-radius: 6px;
      transition: background-color 0.3s ease;
      display: inline-block;
    }
    #addToCartBtn:hover, #buyNowBtn:hover {
      background-color: darkblue;
    }
    .comments {
      margin-top: 40px;
      padding: 20px;
      border-top: 1px solid #ddd;
    }
    .comments h2 {
      margin-bottom: 15px;
      text-align: center;
      color: #0d08a7;
    }
    .comment-box {
      width: 100%;
      min-height: 100px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: vertical;
      font-size: 1rem;
    }
    #submitCommentBtn {
      margin-top: 10px;
      padding: 10px 20px;
      font-size: 1rem;
      background-color: #00ccff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      color: #000;
      font-weight: bold;
      display: block;
      margin-left: auto;
    }
    #submitCommentBtn:hover {
      background-color: #0099cc;
      color: white;
    }
    .comments-list {
      margin-top: 25px;
      max-height: 200px;
      overflow-y: auto;
      border-top: 1px solid #eee;
      padding-top: 15px;
    }
    .comment-item {
      background: #f0f8ff;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      font-size: 1rem;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="image5.jpg" alt="Logo" />
    </div>
    <nav class="nav">
      <a href="home.php">Home</a>
      <a href="product.php" class="active">Products</a>
      <a href="cart.php">Cart</a>
      <a href="login.php">Login</a>
      <a href="terms and condition.html">Terms</a>
    </nav>
  </header>

  <div class="container">
    <h1 id="productName">Product Name</h1>
    <img id="productImage" src="" alt="Product Image" class="product-image" />
    <p class="price" id="productPrice">Rs.0</p>

    <div style="text-align:center;">
      <button id="addToCartBtn">Add to Cart</button>
      <button id="buyNowBtn">Buy Now</button>
    </div>

    <div class="comments">
      <h2>Comments / Reviews</h2>
      <textarea id="commentBox" class="comment-box" placeholder="Write your review here..."></textarea>
      <button id="submitCommentBtn">Submit Review</button>
      <div class="comments-list" id="commentsList"></div>
    </div>
  </div>

  <script>
    function getQueryParams() {
      const params = {};
      const queryString = window.location.search.substring(1);
      const vars = queryString.split('&');
      vars.forEach(v => {
        const [key, val] = v.split('=');
        params[decodeURIComponent(key)] = decodeURIComponent(val || '').replace(/\+/g, ' ');
      });
      return params;
    }

    function displayProductDetails() {
      const params = getQueryParams();
      const name = params.name || 'Unknown Product';
      const price = params.price || '0';
      const id = params.id || '0';

      document.getElementById('productName').textContent = name;
      document.getElementById('productPrice').textContent = `Rs.${price}`;

      let imagePath = '';

      if (params.image) {
        imagePath = `uploads/${params.image}`;
      } else {
        imagePath = `uploads/product${id}.jpg`;
      }

      console.log("Image Path:", imagePath);

      const imgElem = document.getElementById('productImage');
      imgElem.src = imagePath;
      imgElem.alt = name;

      imgElem.onerror = function() {
        imgElem.src = 'default-image.jpg'; // Put a default image in your project folder
        console.warn("Image failed to load, fallback applied.");
      };

      window.currentProduct = { id, name, price };
    }

    function addComment(text) {
      if (!text.trim()) return;
      const commentsList = document.getElementById('commentsList');
      const div = document.createElement('div');
      div.className = 'comment-item';
      div.textContent = text;
      commentsList.appendChild(div);
    }

    document.getElementById('submitCommentBtn').addEventListener('click', () => {
      const box = document.getElementById('commentBox');
      const comment = box.value.trim();
      if (!comment) {
        alert('Please enter a comment.');
        return;
      }
      addComment(comment);
      box.value = '';
    });

    document.getElementById('addToCartBtn').addEventListener('click', () => {
      const { id, name, price } = window.currentProduct;
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&name=${encodeURIComponent(name)}&price=${price}`
      })
      .then(res => res.text())
      .then(data => {
        if (data.trim() === 'success') alert('Added to cart!');
        else alert('Error: ' + data);
      })
      .catch(() => alert('Request failed.'));
    });

    document.getElementById('buyNowBtn').addEventListener('click', () => {
      const { name, price } = window.currentProduct;
      alert(`Proceeding to buy:\n${name} - Rs.${price}`);
    });

    displayProductDetails();
  </script>
</body>
</html>
