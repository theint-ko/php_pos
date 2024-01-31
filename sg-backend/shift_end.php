<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');

$today_dt  = date('Y-m-d H:i:s');
$user_id   = (isset($_SESSION['id'])) ? $_SESSION['id'] : $_COOKIE['id'] ;

$unpaid_sql = "SELECT count(id) AS cashTotal FROM `order` WHERE deleted_at is NULL AND status='0'";
$unpaid_check_result = $mysqli->query($unpaid_sql);
$unpaid_check_row = $unpaid_check_result->fetch_assoc();
if ($unpaid_check_row['cashTotal'] > 0) {
    $url = $cp_base_url . "shift.php?err=unpaid_orders";
    header("Refresh:0, url=$url");
    exit();
}

$shift_check_sql = "SELECT count(id) AS total FROM `shift` WHERE started_date_time is not NULL AND end_date_time is NULL";
$shift_check_result = $mysqli->query($shift_check_sql);
$shift_check_row = $shift_check_result->fetch_assoc();

if ($shift_check_row['total'] <= 0) {
    // Redirect or handle the case where there is no open shift
    $url = $cp_base_url . "shift.php?err=no_open_shift";
    header("Refresh:0, url=$url");
    exit();
}

$query = "UPDATE `shift` SET end_date_time='$today_dt', updated_at ='$today_dt', updated_by='$user_id' WHERE end_date_time IS NULL";
$result = $mysqli->query($query);

$url = $cp_base_url . "shift.php?err=shift_closed";
header("Refresh:0, url=$url");
exit();
?>
