<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = file_get_contents("php://input");
    $orderDetails = json_decode($post_data, true);
    $total=$orderDetails['sub_total'];
    $shift_id=$orderDetails['shift_id'];
    // Begin a transaction
    mysqli_begin_transaction($mysqli);
    $today_dt = date('y-m-d H:i:s');


    try {
        // Insert data into the order table
        $orderQuery = "INSERT INTO `order` (total,shift_id,created_at, created_by, updated_at, updated_by) 
                   VALUES ('$total','$shift_id','$today_dt', '$admin_enable_status', '$today_dt', '$admin_enable_status')";

        $resultOrder = $mysqli->query($orderQuery);

        if (!$resultOrder) {
            throw new Exception("Error inserting data into the order table: " . mysqli_error($mysqli));
        }

        // Get the last inserted order_id
        $order_id = $mysqli->insert_id;

        // Insert data into the order_detail table
        $orderDetailQuery = "INSERT INTO order_detail (item_id, order_id, quantity, price, discount, sub_total, created_at, created_by, updated_at, updated_by) 
                     VALUES ";

        foreach ($orderDetails['items'] as $item) {
            $subTotal = ($item['price'] - $item['discount']) * $item['quantity'];
            $orderDetailQuery .= "(" . $item['id'] . ", " . $order_id . ", " . $item['quantity'] . ", " . $item['price'] . ", " . $item['discount'] . ", " . $subTotal . ", '$today_dt', '$admin_enable_status', '$today_dt', '$admin_enable_status'), ";
        }

        // Remove the trailing comma and execute the query
        $orderDetailQuery = rtrim($orderDetailQuery, ', ');
        $resultOrderDetail = $mysqli->query($orderDetailQuery);

        if (!$resultOrderDetail) {
            throw new Exception("Error inserting data into the order_detail table: " . mysqli_error($mysqli));
        }

        // Commit the transaction
        mysqli_commit($mysqli);

        // Send success response
        http_response_code(200);
        echo json_encode(['message' => 'Order placed successfully']);
    } catch (Exception $e) {
        // Rollback the transaction on exception
        mysqli_rollback($mysqli);

        // Send error response
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    
}

?>