<?php
// Include the database connection
require_once('db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	


        // Retrieve the product ID from the form
        $product_id = $_POST['product_id'];
		
        // Check if the product is already in the cart for the current user
        $user_id = $_SESSION['user_id'];		
        $check_query = "SELECT * FROM Cart WHERE user_id = $user_id AND product_id = $product_id";
        $check_result = mysqli_query($connection, $check_query);
		
        if (!$check_result) {
            die("Database query failed.");
        }

        if (mysqli_num_rows($check_result) > 0) {
            // Product is already in the cart, update quantity
            $update_query = "UPDATE Cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
            $update_result = mysqli_query($connection, $update_query);

            if (!$update_result) {
                die("Database query failed.");
            }
        } else {
            // Product is not in the cart, insert a new row
            $insert_query = "INSERT INTO Cart (user_id, product_id, quantity, date_added) VALUES ($user_id, $product_id, 1, NOW())";
            $insert_result = mysqli_query($connection, $insert_query);

            if (!$insert_result) {
                die("Database query failed.");
            }
        }

        // Redirect to the cart page after adding to cart
        header('Location: cart.php');
        exit();
    
}

// Handle other cases or errors here if needed
?>
