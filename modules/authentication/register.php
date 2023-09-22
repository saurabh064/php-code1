<?php
session_start(); // Start a PHP session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Generate a random OTP (You can customize the length)
    $otp = mt_rand(1000, 9999);

    // Store user data in session
    $_SESSION['registration_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => $password,
        'phone_number' => $phone_number,
        'address' => $address,
        'otp' => $otp,
    ];

    // Send OTP to the user's email (You need to implement email sending)
    $subject = 'Your OTP for Registration';
    $message = "Your OTP is: $otp";

    // Use a mail function or library like PHPMailer to send the email
    // Example using PHP's mail function (make sure your server is configured for email):
    // mail($email, $subject, $message);

    // Redirect the user to the OTP verification page
    header('Location: otp.php');
    exit();
}
?>

<!-- Display the registration form -->
