<?php
// Include the database connection
require_once('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $email = $_POST['email'];

    // Check if the email exists in the User table
    $query = "SELECT * FROM User WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a password reset token and store it in the ResetToken table
        $token = generateResetToken();
        $token_query = "INSERT INTO ResetToken (email, token) VALUES (:email, :token)";
        $token_stmt = $pdo->prepare($token_query);
        $token_stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $token_stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $token_stmt->execute();

        // Send an email to the user with a password reset link (containing the token)
        sendPasswordResetEmail($email, $token);

        echo 'An email with instructions for resetting your password has been sent to your email address.';
    } else {
        echo 'Email not found. Please try again.';
    }
}

// Function to generate a password reset token
function generateResetToken() {
    return bin2hex(random_bytes(32));
}

// Function to send the password reset email (simplified example)
function sendPasswordResetEmail($email, $token) {
    $subject = 'Password Reset Request';
    $message = "To reset your password, click the following link: reset_password.php?token=$token";
    $headers = 'From: your@example.com'; // Replace with your email address
    mail($email, $subject, $message, $headers);
}
?>
