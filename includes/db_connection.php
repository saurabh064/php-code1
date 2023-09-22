<?php
// Database configuration
$host = "db4free.net"; // Replace with your database host
$username = "shikhaper"; // Replace with your database username
$password = "shikhaper@18"; // Replace with your database password
$database = "assignment_persi"; // Replace with your database name

// Create a MySQLi connection
$connection = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Set character set to UTF-8 (optional, but recommended)
$connection->set_charset("utf8");
?>
