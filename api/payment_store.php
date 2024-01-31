<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');
require('../include/include_function.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = file_get_contents("php://input");
    $decode_data = json_decode($post_data, true);
    $id  = $decode_data['id'];
    $order_no=$decode_data['order_no'];
    $customer_pay =  $decode_data['total_cus_pay'];
    $refund =  $decode_data['refund'];
    $kyats =  $decode_data['kyats'];
    $update_payment="UPDATE `order` SET 
                    payment='$customer_pay',
                    refund='$refund',
                    status='1'
                    WHERE id ='$id'";              
        
    $mysqli->query($update_payment);
    foreach($kyats as $kyat){
        $cash=$kyat['cash'];
        $quantity=$kyat['quantity'];
        $ins_kyat = "INSERT INTO `payment_history` (order_id,cash,quantity)
                    VALUES('$id','$cash','$quantity')";

        $mysqli->query($ins_kyat);
    }
    $data=['success'=>true];
    echo json_encode($data);
}
    ?>
