<?php
session_start();
include 'connection.php';

$productId = $_POST['itemId'];

// Initialize an empty HTML content variable
$htmlContent = '';

$sql = "SELECT * FROM item_catalog WHERE item_id = '$productId'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

$name = $row['name'];
$image = $row['image'];
$price = $row['price'];
$description = $row['description'];
$units = $row['units'];
$vendor = $row['vendor'];

//Generate Html content for item
$itemHtml = '
    <div class="col-12 col-md-6 col-lg-5" id="image-column">
		<div class="container mb-3" id="image-container">
            <img src="../'.$image.'">
		</div>
	</div>

	<div class="col-12 col-md-6 col-lg-7" id="info-column">
		<div class="container mb-4" id="product-info">
            <h3 id="item-name"><b>'.$name.'</b></h3>
            <hr>
            <div class="mt-4">
                <p>
                    <span class="font weight bold"><b>Vendor: </b></span>
                    <span id="vendor">'.$vendor.'</span>
                </p>
                <p>
                    <span class="font-weight-bold"><b>Price: </b>Ksh</span>
                    <span id="item-price">'.$price.'</span>
                </p>
            </div>
            <div class="mt-4 mb-3">
                <h5 class="mb-0"><b>Item Description</b></h5>
                <p>'.$description.'</p>
            </div>
            <div class="mt-3">
            <p><span class="font weight bold"><b>Units Left: </b></span>'.$units.'</p>
            </div>
            <div class="input-container">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-dark" type="button" id="minus-btn"><i class="fa fa-minus" id="quantity-icon"></i></button>
                    </div>
                    <input type="text" class="input-quantity" value="1" id="quantity" data-available-units=" '.$units.' ">
                    <div class="input-group-append">
                        <button class="btn btn-outline-dark" type="button" id="plus-btn"><i class="fa fa-plus" id="quantity-icon"></i></button>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-outline-dark" id="wish-button">Add to wishlist<i class="fas fa-heart" id="button-icon" me-5 p-3></i></button>
                <button class="btn btn-dark" id="cart-button">Add to cart<i class="fas fa-shopping-cart" id="button-icon" me-5 p-3></i></button>
            </div>

		</div>
	</div>';

// Append the item HTML content to the overall content
$htmlContent .= $itemHtml;

// Echo the HTML content to be returned to the JavaScript
echo $htmlContent;

?>