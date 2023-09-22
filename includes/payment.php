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
				
               }
			$totalAmount = number_format($totalAmount, 2) ;
            $order_date = date('Y-m-d H:i:s');
			$insert_order_query = "INSERT INTO Orders (user_id, order_date, total_amount) VALUES ($user_id, '$order_date', $totalAmount)";
			$insert_order_result = mysqli_query($connection, $insert_order_query);
			$order_id = mysqli_insert_id($connection);
			echo $order_id;

        if (!$insert_order_result) {
            die("Failed to create order.");

        }
		mysqli_stmt_data_seek($stmt, 0);
		while (mysqli_stmt_fetch($stmt)) {
				echo '123';
				$subtotal = $quantity * $price;
				$insert_order_details_query = "INSERT INTO OrderDetails (order_id, product_id, quantity, subtotal_amount) VALUES ($order_id, $product_id, $quantity, $subtotal)";
            $insert_order_details_result = mysqli_query($connection, $insert_order_details_query);

            if (!$insert_order_details_result) {
                die("Failed to add order details.");
            }
			 $delete_cart_item_query = "DELETE FROM Cart WHERE user_id = $user_id AND product_id = $product_id";
            $delete_cart_item_result = mysqli_query($connection, $delete_cart_item_query);
			
			 
				
               }
			   $payment_date = date('Y-m-d H:i:s');
        $payment_amount = $totalAmount;
        $payment_status = 'Paid';
        $payment_method = 'Credit Card'; // You can adjust this based on payment integration

        $insert_payment_query = "INSERT INTO PaymentTransactions (order_id, payment_date, payment_amount, payment_status, payment_method) VALUES ($order_id, '$payment_date', $payment_amount, '$payment_status', '$payment_method')";
        $insert_payment_result = mysqli_query($connection, $insert_payment_query);
		header('Location: ../index.php'); // Replace with your desired landing page
        exit();
           
        } 

        mysqli_stmt_close($stmt);
    } else {
        echo 'Database query failed.';
    }
    
    
           

        
    
} else {
    echo 'Please log in to complete your order.';
}

mysqli_close($connection);
?>
