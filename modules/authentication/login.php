<?php
// Include the database connection
require_once('../../includes/db_connection.php');

session_start(); // Start a PHP session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the User table to check credentials using MySQLi
    $query = "SELECT * FROM Users WHERE username = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
	echo $password;
	echo $user['password_hash'];
    if ($user && password_verify($password, $user['password_hash'])) {
        // User is authenticated; store user information in the session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['username'];

        // Redirect to a dashboard or home page after successful login
        header('Location: ../../index.php'); // Replace with your desired landing page
        exit();
    } else {
        // Authentication failed; display an error message
        echo 'Invalid email or password. Please try again.';
    }
}

// If not logged in, show the login form
?>
