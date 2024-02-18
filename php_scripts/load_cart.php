<?php
session_start();
include('../php_scripts/connection.php');

// Function to calculate total cost of all items
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


// Check if the user is logged in (implement proper user authentication)
if (isset($_SESSION['user_id'])) {
    // Initialize an empty HTML content variable
    $htmlContent = '';

    // Initialize a variable to store the total item count
    $totalItemCount = 0;

    // Retrieve user id
    $user_id = $_SESSION['user_id'];

    // Retrieve data from database
    $sql = "SELECT cart.*, item_catalog.vendor, item_catalog.units FROM cart LEFT JOIN item_catalog ON cart.item_id = item_catalog.item_id WHERE cart.user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $cart_id = $row['cart_id'];
            $item_name = $row['item_name'];
            $item_price = $row['item_price'];
            $quantity = $row['quantity'];
            $image = $row['item_image'];
            $vendor = $row['vendor'];
            $units = $row['units'];

            // Determine the status based on the units value
            $status = ($units > 0) ? 'In Stock' : 'Out of Stock';

            // Increment the total item count
            $totalItemCount += $quantity;

            // Generate HTML content for each item
            $itemHtml =
            '<div class="cart-item" data-cart-id="'.$cart_id.'">
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4" id="item-image">
                        <img src="'.$image.'" alt="'.$item_name.' image">
                    </div>
                    <div class="col-sm-5 col-md-5 col-lg-6" id="item-info">
                        <div class="mt-1">
                            <p><b>'.$item_name.'</b></p>
                            <small><span><b>Vendor: </b></span> <span id="vendor">'.$vendor.'</span></small><br>
                            <small><span><b>Status: </b></span> <span>'.$status.'</span></small>
                        </div>
                        <div class="mt-2">
                            <div class="mb-md-2">
                                <button class="btn custom-btn btn-sm" type="button" id="minus-btn-'.$cart_id.'"><i class="fa fa-minus" id="quantity-icon"></i></button>
                                <input type="text" value="'.$quantity.'" class="input-quantity" data-available-units=" '.$units.' " id="quantity-'.$cart_id.'"/>
                                <button class="btn custom-btn btn-sm" type="button" id="plus-btn-'.$cart_id.'"><i class="fa fa-plus" id="quantity-icon"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-2" id="item-price">
                        <div class="mt-1">
                            <span><b>Ksh </b></span>
                            <span><b>'.$item_price.'</b></span>
                        </div>
                        <div id="remove" class="mt-sm-5 pt-sm-4">
                            <button class="btn custom-btn btn-sm" type="button" id="remove-btn-'.$cart_id.'"><i class="fa fa-trash"></i> <b>Remove</b></button>
                        </div>
                    </div>
                </div>
            </div>';

            // Append the item HTML content to the overall content
            $htmlContent .= $itemHtml;
        }

        // Calculate total cost
        $totalCost = calculateTotalCost($user_id, $conn);

        // Create an array with both the HTML content and the total item count
        $responseData = [
            'htmlContent' => $htmlContent,
            'totalItemCount' => $totalItemCount,
            'totalCost' => $totalCost
        ];

        // Return the data as JSON
        echo json_encode($responseData);
    }
    else{
        // Cart is empty, display message
        $emptyMessage = '<div class="empty-message text-center mt-5 mb-5">
                            <p>
                               <b>Your cart is empty!</b><br>
                                Visit our Item Catalogue page and browse our categories to discover the best items for you<br>
                                <button class="btn custom-btn mt-4" type="button" id="catalogue-button"><b>Visit Catalogue page</button>
                            </p>
                        </div>';

        // Append message to overall content
        $htmlContent .= $emptyMessage;

        // Create an array with both the HTML content and the total item count
        $responseData = [
            'htmlContent' => $htmlContent,
            'totalItemCount' => 0,
            'totalCost' => 0
        ];

        // Return the data as JSON
        echo json_encode($responseData);

    }
}
else {
    echo json_encode(['message' => 'User not authenticated. Try logging in|error']);
}

?>