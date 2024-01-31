<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/check_cashier_authentication.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
   $post_data  = file_get_contents("php://input");
   $decode_data= json_decode($post_data,true);
   $parent_id  = (int)($decode_data['parent_id']);

$parent_cat_sql ="SELECT id,name,image FROM `category` WHERE parent_id ='$parent_id' AND
                status= '$enable_status' AND deleted_at is NULL ";

   $parent_cat_res = $mysqli->query($parent_cat_sql);
   $data = [];
   while($parent_cat_row = $parent_cat_res->fetch_assoc()){
    $res_data =[];
    $cat_id    = (int)($parent_cat_row['id']);
    $cat_name  = htmlspecialchars($parent_cat_row['name']);
    $cat_image = $parent_cat_row['image'];
    $res_data ['id']   =$cat_id;
    $res_data ['name'] =$cat_name;
    $res_data ['image']=$cat_image;
    array_push($data,$res_data);
   }   
   echo json_encode($data); 
}         
?>