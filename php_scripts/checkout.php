<?php
session_start();
include "../php_scripts/connection.php";

// Check if user is logged in (user authentication)
if (isset($_SESSION['user_id'])) {
    // Retrieve checkout info
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phoneNumber'];
    $additional_phone_number = $_POST['additionalPhoneNumber'];
    $home_address = $_POST['homeAddress'];
    $additional_address_info = $_POST['additionalAddressInfo'];
    $region = $_POST['region'];
    $city = $_POST['city'];
    $delivery_option = $_POST['deliveryOption'];
    $payment_method = $_POST['paymentMethod'];
    $item_count = $_POST['itemCount'];
    $item_sub_total = $_POST['itemSubTotal'];
    $delivery_fee = $_POST['deliveryFee'];
    $total = $_POST['total'];

    // Convert into integers
    $item_count = (int)$item_count;
    $item_sub_total = (int)$item_sub_total;
    $delivery_fee = (int)$delivery_fee;
    $total = (int)$total;

    // Insert info into database
    $sql = "INSERT INTO orders (user_id, name, email, phone_number, additional_phone_number, home_address, additional_address_info, region, city, delivery_option, payment_method, item_count, item_sub_total, delivery_fee, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("issssssssssiiii", $user_id, $name, $email, $phone_number, $additional_phone_number, $home_address, $additional_address_info, $region, $city, $delivery_option, $payment_method, $item_count, $item_sub_total, $delivery_fee, $total);

    if ($stmt->execute()) {
        echo "Order placed successfully|success";
    } else {
        echo "An error occurred while placing the order|error";
        error_log("Error: " . $stmt->error);
    }

} else {
    echo "User not authenticated. Try logging in first|error";
}
?>