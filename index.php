<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naykii Beauty - Home</title>
    <link rel="stylesheet" href="assets\css\styles.css">
</head>
<!-- Include jQuery library in your HTML -->


<body>
    <header>
        <div class="logo">
            <!-- Your logo image goes here -->
            <img src="image\logo.png" alt="Naykii Beauty & Skin Care">
        </div>
        <nav>
            <!-- Include your headers.php content here -->
            <?php include('includes/header.php'); ?>
        </nav>
        <div class="user-actions">
            <!-- Add login and signup buttons here -->
            <a href="public_html/login.html" class="btn">Login</a>
            <a href="public_html/register.html" class="btn">Signup</a>
            <!-- Add a button to go to the cart -->
            <a href="includes/cart.php" class="btn">Go to Cart</a>
        </div>
    </header>
    
    <main>
        <!-- Your product listing code here -->
        <?php
            // Include your database connection and fetch product data here
            include('includes/db_connection.php');
            
            // Example code to fetch and display products
            $query = "SELECT * FROM Products";
            $result = mysqli_query($connection, $query);

            if (!$result) {
                die("Database query failed.");
            }

            while ($row = mysqli_fetch_assoc($result)) {
                // Display product information here
                echo '<div class="product-box">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_data']) . '" alt="' . $row['product_name'] . '">';
                echo '<h3>' . $row['product_name'] . '</h3>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<p>Price: $' . $row['price'] . '</p>';
                // Add an "Add to Cart" button with the product ID
               
               
                 echo '<button class="add-to-cart-button" data-product-id="' . $row['product_id'] . '">Add to Cart</button>';
               
                echo '</div>';
            }

            mysqli_free_result($result);
            mysqli_close($connection);
        ?>
    </main>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('.add-to-cart-button').click(function (e) {
            e.preventDefault(); // Prevent the default form submission

            var product_id = $(this).data('product-id');
            var quantity = 1; // You can adjust the quantity as needed

            $.ajax({
                type: 'POST',
                url: 'includes/addToCart.php',
                data: { action: 'add_to_cart', product_id: product_id, quantity: quantity },
                success: function (response) {
                    alert(product_id); // Display a success message to the user
                },
                error: function () {
                    alert('Error occurred while adding the product to the cart');
                }
            });
        });
    });
</script>

    <footer>
        <!-- Footer content here -->
    </footer>
</body>
</html>
