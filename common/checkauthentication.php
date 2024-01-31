<?php
$autenticaton_check=false;
$user_id=null;
if(isset($_SESSION['id']) && isset($_SESSION['username']) ){
  $user_id=$_SESSION['id'];
}

if((isset($_COOKIE['id'])) && isset($_COOKIE['username']) ){
      $user_id=$_COOKIE['id'];
       
}
  if($user_id!=null){
    $auth_sql="SELECT count(id) AS total FROM user WHERE id='$user_id' AND 
    deleted_at IS NULL AND deleted_by IS NULL";

    $auth_res=$mysqli->query($auth_sql);
  
    $auth_row=$auth_res->fetch_assoc();
    $total=$auth_row['total'];

    if($total>0){
      $autenticaton_check=true;
    }
  }
    if($autenticaton_check==false){
      $url=$cp_base_url . "login.php";
      header("Refresh:0,url=$url");
      exit();
    }
  


?>