<?php
session_start();
require_once('db_connection.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Retrieve user input
        $email = $_POST['email'];
        $security_question_id = $_POST['security_question_id'];
        $security_answer = $_POST['security_answer'];
        $new_password = $_POST['new_password'];

        // Check if the provided email and security answer match
        $query = "SELECT u.UserID
                  FROM Users u
                  JOIN PasswordReset pr ON u.UserID = pr.UserID
                  WHERE u.Email = ? 
                  AND pr.SecurityQuestionID = ? 
                  AND pr.SecurityAnswer = ?";

        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $email, $security_question_id, $security_answer);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 1) {
                // User email and security answer match; update the password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE Users SET Password = ? WHERE UserID = ?";
                $update_stmt = mysqli_prepare($connection, $update_query);

                if ($update_stmt) {
                    mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $user_id);
                    mysqli_stmt_execute($update_stmt);

                    if (mysqli_stmt_affected_rows($update_stmt) > 0) {
                        // Password updated successfully
                        echo 'Password updated successfully. You can now <a href="login.html">login</a> with your new password.';
                    } else {
                        echo 'Password update failed.';
                    }

                    mysqli_stmt_close($update_stmt);
                } else {
                    echo 'Database query failed.';
                }
            } else {
                echo 'Invalid email, security question, or answer.';
            }

            mysqli_stmt_close($stmt);
        } else {
            echo 'Database query failed.';
        }
    }
}

mysqli_close($connection);
?>
