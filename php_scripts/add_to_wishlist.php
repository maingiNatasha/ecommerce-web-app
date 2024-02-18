<?php
session_start();
include 'connection.php';

// Check if the user is logged in (implement proper user authentication)
if (isset($_SESSION['user_id'])) {
    // Retrieve product info
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['itemId'];
    $item_name = $_POST['itemName'];
    $item_price = $_POST['itemPrice'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    // Insert data to database
    $sql = "INSERT INTO wishlist (user_id, item_id, item_name, item_price, quantity, item_image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisiis", $user_id, $item_id, $item_name, $item_price, $quantity, $image);

    if ($stmt->execute()) {
        echo "Item added to wishlist|success";
    } else {
       echo "Error occurred while adding item to wishlist|error";
    }
} else {
    echo "User not authenticated. Try Logging in first|error";
}

?>