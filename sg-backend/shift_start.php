<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');

$today_dt  = date('Y-m-d H:i:s');
$user_id   = (isset($_SESSION['id'])) ? $_SESSION['id'] : $_COOKIE['id'] ;
$shift_check_sql = "SELECT count(id) AS total FROM `shift` WHERE started_date_time is not NULL AND end_date_time is NULL";
$shift_check_result = $mysqli->query($shift_check_sql);

while($shift_check_row = $shift_check_result->fetch_assoc()){
    $shift_check_rows  = $shift_check_row['total'];
}

if($shift_check_rows>0){
    $url=$cp_base_url."shift.php?err=create";
    header("Refresh:0,url=$url");
    exit();
}

else{

$sql = "INSERT INTO shift (started_date_time,created_at,created_by,updated_at,updated_by)
          VALUES ('$today_dt','$today_dt','$user_id','$today_dt','$user_id')";

$result =$mysqli->query($sql);
$url=$cp_base_url."shift.php";
header("Refresh:0,url=$url");
exit();

}
?>



    