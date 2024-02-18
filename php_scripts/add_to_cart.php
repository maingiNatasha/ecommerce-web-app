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

    // Check if item exists in cart
    $sql_check = "SELECT * FROM cart WHERE user_id = ? AND item_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $item_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Item already exists in cart, so update the quantity
        $row = $result_check->fetch_assoc();
        $original_quantity = $row['quantity'];
        $new_quantity = $original_quantity + $quantity;

        $sql_update = "UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $new_quantity, $user_id, $item_id);

        if ($stmt_update->execute()) {
            echo "Item quantity updated in cart|success";
        } else {
            echo "Error occurred while updating item quantity in cart|error";
        }
    }
    else {
        // Item doesn't exist in cart, so insert item
        $sql = "INSERT INTO cart (user_id, item_id, item_name, item_price, quantity, item_image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisiis", $user_id, $item_id, $item_name, $item_price, $quantity, $image);

        if ($stmt->execute()) {
            echo "Item added to cart|success";
        } else {
        echo "Error occurred while adding item to cart|error";
        }
    }

} else {
    echo "User not authenticated. Try Logging in first|error";
}

?>