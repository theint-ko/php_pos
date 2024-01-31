<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
   $post_data  = file_get_contents("php://input");
   $decode_data= json_decode($post_data,true);
   $category_id  = (int)($decode_data['category_id']);

$item_sql ="SELECT id,name,image,price,code_no FROM `item` WHERE category_id ='$category_id' AND
                status= '$enable_status' AND deleted_at is NULL ";

   $item_result = $mysqli->query($item_sql);
   
   $data = [];
   while($item_row = $item_result->fetch_assoc()){
    $res_data =[];
    $item_id    = (int)($item_row['id']);
    $item_name  = htmlspecialchars($item_row['name']);
    $item_image = $item_row['image'];
    $item_code = $item_row['code_no'];
    $item_price = $item_row['price'];

    $res_data ['id']   =$item_id;
    $res_data ['name'] =$item_name;
    $res_data ['image']=$item_image;
    $res_data ['code'] =$item_code;
    $res_data ['price'] =$item_price;
    array_push($data,$res_data);
   }   
   echo json_encode($data); 
}         
?>