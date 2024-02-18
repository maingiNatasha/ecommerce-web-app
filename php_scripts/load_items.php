<?php
    session_start();
    include ('../php_scripts/connection.php');

    // Retrieve the selected category from the AJAX request
    $selectedCategory = $_POST['category'];

    // Initialize an empty HTML content variable
    $htmlContent = '';

    // Construct the SQL query based on the selected category
    if ($selectedCategory === 'all') {
        // Fetch all items
        $sql = "SELECT * FROM item_catalog";
    }
    elseif ($selectedCategory === 'outfits') {
        $sql = "SELECT * FROM item_catalog WHERE item_type = 'outfit set'";
    }
    elseif ($selectedCategory === 'clothes') {
        $sql = "SELECT * FROM item_catalog WHERE item_type IN ('tops', 't-shirts', 'shirts', 'bottoms', 'trousers', 'shorts', 'outfit set')";
    }
    elseif ($selectedCategory === 'shoes') {
        $sql = "SELECT * FROM item_catalog WHERE item_type IN ('sneakers', 'heels', 'boots')";
    }
    else {
        // Fetch items for the selected category
        $sql = "SELECT * FROM item_catalog WHERE item_type = '$selectedCategory'";
    }

    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)) {
        $id = $row['item_id'];
        $name = $row['name'];
        $image = $row['image'];
        $price = $row['price'];
        $units = $row['units'];

        // Generate HTML content for each item
        $itemHtml =
        '<div class="col-md-6 col-lg-4" id="card-col-container">
            <div class="card h-100" data-item="'.$id.'" data-item-name="'.$name.'">
                <img src="../'.$image.'" class="card-img-top">
                <div class="card-body" id="item-info">
                    <div class="d-flex justify-content-between">
                        <span><b>'.$name.'</b></span> <span id="item-price"><b>Ksh '.$price.'</b></span>
                    </div>
                    <p class="card-text mt-1">Units left: '.$units.'</p>
                    <a href="#" class="stretched-link"></a>
                </div>
                <hr>
                <div class="card-body" id="btn-card-body">
                    <div class="text-right buttons">
                        <button class="btn btn-outline-dark" id="wish-button">Add to wishlist<i class="fas fa-heart" id="button-icon"></i></button>
                        <button class="btn btn-dark" id="cart-button">Add to cart<i class="fas fa-shopping-cart" id="button-icon"></i></button>
                    </div>
                </div>
            </div>
        </div>';

        // Append the item HTML content to the overall content
        $htmlContent .= $itemHtml;

    }

    // Echo the HTML content to be returned to the JavaScript
    echo $htmlContent;

?>
