<?php
// Start the session
session_start();

// Set up the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "giftz";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all items from the database
$sql = "SELECT * FROM cakes";
$result = $conn->query($sql);

// Check if there are any items
if ($result->num_rows > 0) {
    // Output each item
    while($row = $result->fetch_assoc()) {
        //$_SESSION['name'] = $row['name'];
        echo "<div>";
        echo "<h3>" . $row["name"] . "</h3>";
        echo "<img src='" . $row["filename"] . "'>";
        echo "<p>Price: $" . $row["price"] . "</p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='item_id' value='" . $row["id"] . "'>";
        echo "<input type='submit' name='add_to_cart' value='Add to Cart'>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "No items found.";
}

// Check if the "Add to Cart" button was clicked
if(isset($_POST["add_to_cart"])) {
    // Get the item ID from the form data
    $item_id = $_POST["item_id"];

    // Add the item ID to the shopping cart array in the session variable
    if(!isset($_SESSION["shopping_cart"])) 
    {
        $_SESSION["shopping_cart"] = array();
    }
    array_push($_SESSION["shopping_cart"], $item_id);
}

// Display the shopping cart
if(isset($_SESSION["shopping_cart"])) {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach($_SESSION["shopping_cart"] as $item_id) {
        // Retrieve the item information from the database
        $sql = "SELECT * FROM items WHERE id = $item_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Display the item in the shopping cart
        echo "<li>" . $row["name"] . " ($" . $row["price"] . ")</li>";
    }
    echo "</ul>";
}

// Close the database connection
$conn->close();
?>
