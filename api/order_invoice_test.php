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

    $order_sql = "SELECT id, total, created_at, payment, refund,
                CONCAT('$shift_id', '-', id, DATE_FORMAT(created_at, '%Y%m%d')) as order_no 
                FROM `order` WHERE id = '$id' AND deleted_at IS NULL";

    $order_voice_res = $mysqli->query($order_sql);

    $order_detail_res="SELECT T01.quantity,T01.sub_total,T02.name,T02.price FROM order_detail T01 
                        LEFT JOIN item T02 ON T01.item_id=T02.id 
                        WHERE T01.order_id='$id' AND T01.deleted_at IS NULL
                        AND T02.deleted_at IS NULL";

    $detail_result=$mysqli->query($order_detail_res);
    $detail_data=[];
    while($detail_row = $detail_result->fetch_assoc()){
    $detail_res_data =[];
    $item_name   = htmlspecialchars($detail_row['name']);
    $quantity    = $detail_row['quantity'];
    $sub_total   = $detail_row['sub_total'];
    $price   = $detail_row['price'];
    $detail_res_data ['name']   =$item_name;
    $detail_res_data ['quantity'] =$quantity;
    $detail_res_data ['sub_total']=$sub_total;
    $detail_res_data ['price']=$price;
    array_push($detail_data,$detail_res_data);
    }

    $data = [];
    while($order_row = $order_voice_res->fetch_assoc()){
    $res_data =[];
    $order_id    =(int)($order_row['id']);
    $total       = $order_row['total'];
    $created_at  =$order_row['created_at'];
    $order_no    =$order_row['order_no'];
    $payment     =$order_row['payment'];
    $refund      =$order_row['refund'];
    $res_data ['id']   =$order_id;
    $res_data ['total'] =$total;
    $res_data ['date']=formatDateDmy($created_at);
    $res_data ['time']=formatDateHI($created_at);
    $res_data ['order_no']=$order_no;
    $res_data ['payment']=$payment;
    $res_data ['refund']=$refund;
    $res_data ['order_detail']=$detail_data;

    array_push($data,$res_data);
    }
    echo json_encode($data);
    }
    ?>
