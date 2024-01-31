<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = file_get_contents("php://input");
    $decode_data = json_decode($post_data, true);
    $id = $decode_data['id'];
    $sub_total = $decode_data['sub_total'];
    $shift_id = $decode_data['shift_id'];
    $item_data =$decode_data['items'];
    $today_dt = date('Y-m-d H:i:s');
    $user_id = $_SESSION['cid'];

    $update_sql = "UPDATE `order` SET total='$sub_total', updated_at='$today_dt',
                    updated_by='$user_id' WHERE id='$id'";

    $update_result = $mysqli->query($update_sql);
    $order_id = $id;

    $delete_sql = "DELETE FROM `order_detail` WHERE order_id='$order_id'";
    $mysqli->query($delete_sql);

    foreach ($item_data as $value) {
        $quantity = $value['quantity'];
        //$item_sub_total = $value['sub_total']; 
        $item_id = $value['id'];
        $price = $value['price'];
        $discount = $value['discount'];

        $ins_detail_sql = "INSERT INTO `order_detail`
                            (quantity, sub_total, order_id, item_id, price, discount, created_at,
                            created_by, updated_at, updated_by)
                            VALUES ('$quantity', '$sub_total', '$order_id', '$item_id', '$price',
                            '$discount', '$today_dt', '$user_id', '$today_dt', '$user_id')";

        $mysqli->query($ins_detail_sql);
    }

    $data = ['success' => true];
    echo json_encode($data);
}
?>
