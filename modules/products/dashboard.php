<!DOCTYPE html>
<html>
<head>
    <title>Product Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to your CSS stylesheet -->
</head>
<body>
    <h2>Product Dashboard</h2>

    <?php
    // Include the database connection
    require_once('db_connection.php');

    // Query to retrieve a list of products
    $query = "SELECT * FROM Product";
    $stmt = $pdo->query($query);

    if ($stmt->rowCount() > 0) {
        echo '<table>';
        echo '<tr><th>Product Name</th><th>Description</th><th>Price</th></tr>';
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['Name'] . '</td>';
            echo '<td>' . $row['Description'] . '</td>';
            echo '<td>$' . number_format($row['Price'], 2) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No products available.';
    }
    ?>

    <!-- Add links or buttons for further actions or navigation -->
    <a href="logout.php">Logout</a> <!-- Example logout link -->
</body>
</html>
