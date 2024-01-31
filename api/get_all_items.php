<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
   $post_data  = file_get_contents("php://input");
   $decode_data= json_decode($post_data,true);
   $item_id  = (int)($decode_data['item_id']);
   $today_dt = date("Y-m-d");

$item_sql ="SELECT id,name,category_id,image,price,code_no FROM `item` WHERE id ='$item_id' AND
                status= '$enable_status' AND deleted_at is NULL ";


   $item_result = $mysqli->query($item_sql);
   $discount_item="SELECT
                        CAST(
                        CASE 
                        WHEN T02.amount IS NOT NULL AND T02.percentage IS NULL THEN T02.amount
                        WHEN T02.amount IS NULL AND T02.percentage IS NOT NULL THEN(T03.price*T02.percentage/100)
                        END
                           AS UNSIGNED)
                           AS total_discount
                        FROM discount_item T01 LEFT JOIN discount_promotion T02 ON T01.discount_id=T02.id
                        LEFT JOIN item T03 
                        ON T03.id=T01.item_id WHERE T03.id='$item_id'
                        AND '$today_dt' BETWEEN T02.start_date AND T02.end_date AND T01.deleted_at IS NULL AND T02.deleted_at IS NULL
                        ";
      $discount_res=$mysqli->query($discount_item);                  

   $data = [];
   while($item_row = $item_result->fetch_assoc()){
    $res_data =[];
    $item_id    = (int)($item_row['id']);
    $item_name  = htmlspecialchars($item_row['name']);
    $item_image = $item_row['image'];
    $item_code  = $item_row['code_no'];
    $item_price = $item_row['price'];

$discount_row=$discount_res->fetch_assoc();
$total_discount=(isset($discount_row['total_discount']))?$discount_row['total_discount']:0;

    $res_data ['id']   =$item_id;
    $res_data ['name'] =$item_name;
    $res_data ['image']=$item_image;
    $res_data ['code'] =$item_code;
    $res_data ['price'] =$item_price;
    $res_data ['quantity'] =1;
    $res_data ['discount'] =$total_discount;
    $res_data ['amount']   =$item_price - $total_discount;

    array_push($data,$res_data);
   }   
   echo json_encode($data); 
}         
?>