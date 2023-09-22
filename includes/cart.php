<html>

<main>
<?php
session_start();
require_once('db_connection.php'); // Include your database connection

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to fetch user's cart contents with product details
    $query = "SELECT Cart.cart_id, Products.product_id, Products.product_name, Products.price, Cart.quantity 
              FROM Cart 
              JOIN Products ON Cart.product_id = Products.product_id 
              WHERE Cart.user_id = ?";
    
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Display cart items
            mysqli_stmt_bind_result($stmt, $cart_id, $product_id, $name, $price, $quantity);
            $totalAmount = 0;
            while (mysqli_stmt_fetch($stmt)) {
				$subtotal = $quantity * $price;
				$totalAmount += $subtotal;
                echo '<p>' . $name . ' - $' . $price . ' - Quantity: ' . $quantity . '</p>';
            }

            // Add a "Proceed to Payment" button that redirects to the payment page
            echo 'Total Amount:' . number_format($totalAmount, 2) ;
			echo '<BR></BR>';
			echo '<a href="../public_html/payment.html">Proceed to Payment</a>';
        } else {
            echo 'Your cart is empty.';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo 'Database query failed.';
    }
} else {
    echo 'Please log in to view your cart.';
}

mysqli_close($connection);
?>
</main>
<body>
<form action="payment.php" method="post" id="paymentForm">
    <label for="payment_method">Select Payment Method:</label>
    <select name="payment_method" id="payment_method">
        <option value="cc">Credit Card</option>
        <option value="cod">Cash on Delivery</option>
    </select>
    <br>

    <div id="cc_details" style="display:block;">
        <label for="credit_card">Credit Card Number:</label>
        <input type="text" name="credit_card" id="credit_card" placeholder="Enter your credit card number">
        <br>
        <label for="cvv">CVV:</label>
        <input type="text" name="cvv" id="cvv" placeholder="Enter CVV">
        <br>
    </div>
    
    <input type="submit" value="Make Payment">
</form>

<script>
    document.getElementById("paymentForm").addEventListener("change", function (e) {
        var ccDetails = document.getElementById("cc_details");
        if (e.target.id === "payment_method") {
            if (e.target.value === "cc") {
                ccDetails.style.display = "block";
            } else {
                ccDetails.style.display = "none";
            }
        }
    });
</script>




</body>
</html>