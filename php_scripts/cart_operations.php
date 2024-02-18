<?php
    session_start();
    include('../php_scripts/connection.php');

    function removeCartItem($cartItemId, $conn) {
        // Delete item from database
        $sql = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cartItemId);

        $response = [];

        if ($stmt->execute()) {
            $response['message'] = 'Item removed from cart|success';
        }
        else {
            $response['message'] = 'Error occurred while removing item|error';
        }

        return $response;
    }

    function updateQuantity($cartItemId, $newQuantity, $conn) {
        // Update item quantity
        $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newQuantity, $cartItemId);

        $response = [];

        if ($stmt->execute()) {
            $response['message'] = 'Item quantity updated|success';
        }
        else {
            $response['message'] = 'Error occurred while updating quantity|error';
        }

        return $response;
    }

    function calculateTotalItemCount($user_id, $conn) {
        // Calculate total quantity of items
        $sql = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        $totalItemCount = 0;

        $stmt->execute();
        $stmt->bind_result($totalItemCount);
        $stmt->fetch();

        return (int)$totalItemCount;
    }

    function calculateTotalCost($user_id, $conn) {
        // Calculate total cost of all items
        $sql = "SELECT SUM(quantity * item_price) AS total_cost FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        $totalCost = 0;

        $stmt->execute();
        $stmt->bind_result($totalCost);
        $stmt->fetch();

        return (float)$totalCost;
    }


    if(isset($_SESSION['user_id'])) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action']) && isset($_POST['cartItemId'])) {
                $action = $_POST['action'];
                $cartItemId = $_POST['cartItemId'];
                $user_id = $_SESSION['user_id'];

                $response = [];

                if($action === 'removeItem') {
                    $response = removeCartItem($cartItemId, $conn);
                }
                elseif ($action === 'updateQuantity' && isset($_POST['newQuantity'])) {
                    $newQuantity = $_POST['newQuantity'];
                    $response = updateQuantity($cartItemId, $newQuantity, $conn);
                }
                else {
                    $response['message'] = 'Wrong action|error';
                }

                // Calculate new item count and total cost
                $totalItemCount = calculateTotalItemCount($user_id, $conn);
                $totalCost = calculateTotalCost($user_id, $conn);

                // Send total item count and total cost as a response
                $response['totalItemCount'] = $totalItemCount;
                $response['totalCost'] = $totalCost;

                // Send the response as JSON
                echo json_encode($response);
            } else {
                echo json_encode(['message' => 'Missing action or Item Id']);
            }

        } else {
            echo json_encode(['message' => 'Method != Post']);
        }

    } else {
        echo json_encode(['message' => 'User not authenticated. Try logging in|error']);
    }

?>