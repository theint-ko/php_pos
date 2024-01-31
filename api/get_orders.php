<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');
require('../include/include_function.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = file_get_contents("php://input");
    $decode_data = json_decode($post_data, true);
    $shift_id = (int) ($decode_data['shift_id']);

    $order_sql = "SELECT id, total, status, created_at, 
                  CONCAT('$shift_id','-', id, DATE_FORMAT(created_at, '%Y%m%d')) as order_no 
                  FROM `order` WHERE shift_id = '$shift_id' AND deleted_at IS NULL ORDER BY 
                  status ASC, id DESC";
                //   echo $order_sql;
                //   exit();

    $order_result = $mysqli->query($order_sql);

   $data = [];
   while($order_row = $order_result->fetch_assoc()){
    $res_data =[];
    $order_id    = (int)($order_row['id']);
    $total_amount= $order_row['total'];
    $status      = $order_row['status'];
    $created_at  =formatDateHIS($order_row['created_at']);
    $order_no =$order_row['order_no'];
    $res_data ['id']   =$order_id;
    $res_data ['total'] =$total_amount;
    $res_data ['status']=$status;
    $res_data ['created_at'] =$created_at;
    $res_data ['order_no'] =$order_no;
    array_push($data,$res_data);
   }   
   echo json_encode($data); 
}         
?>