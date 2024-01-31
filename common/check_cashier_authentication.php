<?php
$authenticaton_check = false;
$user_id = null;
$shift_open=false;
if(isset($_SESSION['cid']) && isset($_SESSION['cusername']) ){
  $user_id = $_SESSION['cid'];

  if($user_id!=null){
    $auth_sql="SELECT count(id) AS total FROM `user` WHERE id='$user_id' AND role='$cashier_role' AND 
              deleted_at IS NULL AND deleted_by IS NULL";

        $auth_res=$mysqli->query($auth_sql);
      
        $auth_row=$auth_res->fetch_assoc();
          $total=$auth_row['total'];
    
        if($total > 0){
          $authenticaton_check=true;
        }
  }

    if($authenticaton_check == false) {
      
        $url = $base_url . "login.php";
        header("Refresh:0,url=$url");
        exit();
    }
  }

    $shift_sql = "SELECT id FROM `shift` WHERE started_date_time IS NOT NULL AND end_date_time IS NULL";
    $shift_result = $mysqli->query($shift_sql);
    $res_row = $shift_result->num_rows;

    if($res_row<=0){

        $url=$cp_base_url."shif_close.php";
        header("Refresh:0,url=$url");
        exit();     
    }
      else{
        while($shift_row=$shift_result->fetch_assoc()){
          $shift_id=$shift_row['id'];
          $_SESSION['shift_id']=$shift_id;
        }
       
    }

  
?>