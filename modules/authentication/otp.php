<?php
session_start(); // Start a PHP session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];

    // Retrieve the OTP and user data stored in the session
    $registration_data = $_SESSION['registration_data'];
    $stored_otp = $registration_data['otp'];
	echo $stored_otp;
	$email=$registration_data['email'];

    // Compare the entered OTP with the stored OTP
    if ($entered_otp == $stored_otp) {
        // OTP is correct; continue with registration
        // Insert user data into the database (You need to create the 'users' table)
        // Example code to insert data into the 'users' table using mysqli:

        // Include the database connection file
        require_once('../../includes/db_connection.php');

        // Prepare and execute the SQL query
        $query = "INSERT INTO Users (first_name, last_name, username, password_hash, phone_number, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $registration_data['first_name'], $registration_data['last_name'], $registration_data['email'], $registration_data['password'], $registration_data['phone_number'], $registration_data['address']);
        
        if (mysqli_stmt_execute($stmt)) {
            // Registration successful; redirect to a success page or login page
            header('Location: ../../registration_success.php');
            exit();
        } else {
            // Registration failed; display an error message
            echo 'Registration failed. Please try again.';
        }

        // Unset the registration data session variable
        unset($_SESSION['registration_data']);
		
		$query = "SELECT * FROM Users WHERE username = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
		
		$_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['username'];
    } else {
        // OTP is incorrect; display an error message
        echo 'Invalid OTP. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body>
    <h2>OTP Verification</h2>
    <form method="post" action="otp.php">
        <label for="otp">Enter OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
