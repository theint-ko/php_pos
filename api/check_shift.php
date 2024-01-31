<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $shift_check_sql = "SELECT count(id) AS total FROM `shift` WHERE started_date_time IS NOT NULL AND end_date_time IS NULL";
    $shift_check_result = $mysqli->query($shift_check_sql);

    if ($shift_check_result) {
        $shift_check_row = $shift_check_result->fetch_assoc();
        $shift_check_rows = $shift_check_row['total'];

        $response = array('total' => $shift_check_rows);
        echo json_encode($response);
    } else {
        // Handle database error
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = file_get_contents("php://input");
    $orderDetails = json_decode($post_data, true);

    // Handle POST data if needed

    // Respond with a success message for the POST request
    echo json_encode(array('message' => 'POST request received successfully'));
} else {
    // Handle other HTTP methods if needed
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('error' => 'Method Not Allowed'));
}

// Close the database connection
$mysqli->close();
?>
