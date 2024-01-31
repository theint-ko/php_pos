<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = file_get_contents("php://input");
    $decode_data = json_decode($post_data, true);
    $id = (int) ($decode_data['id']);
    $status=(int)($decode_data['status']);

    $order_update = "UPDATE `order` SET status='$status' WHERE id = '$id'";
    $order_update_res = $mysqli->query($order_update);
    $data = ['status' => 200];
    echo json_encode($data);
}
?>
