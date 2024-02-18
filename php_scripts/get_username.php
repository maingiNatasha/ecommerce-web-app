<?php
session_start();

// Check if the user is logged in and has a username in the session
if (isset($_SESSION["username"])) {
    $response = array('username' => $_SESSION["username"]);
    echo json_encode($response);
} else {
    // Handle the case when the user is not logged in
    $response = array('username' => 'Guest'); // You can set a default value or handle this differently
    echo json_encode($response);
}
?>
