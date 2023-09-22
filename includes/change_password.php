<?php
// Include the database connection
require_once('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Verify the token in the ResetToken table
    $token_query = "SELECT * FROM ResetToken WHERE token = :token";
    $token_stmt = $pdo->prepare($token_query);
    $token_stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $token_stmt->execute();
    $token_data = $token_stmt->fetch(PDO::FETCH_ASSOC);

    if ($token_data) {
        // Update the user's password in the User table
        $email = $token_data['email'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE User SET password = :password WHERE email = :email";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $update_stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $update_stmt->execute();

        // Delete the used token from the ResetToken table
        $delete_query = "DELETE FROM ResetToken WHERE token = :token";
        $delete_stmt = $pdo->prepare($delete_query);
        $delete_stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $delete_stmt->execute();

        echo 'Password changed successfully. You can now <a href="login.html">log in</a>.';
    } else {
        echo 'Invalid or expired token. Please request another password reset.';
    }
}
?>
