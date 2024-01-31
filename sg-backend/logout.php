<?php
session_start();
require('../common/config.php');
session_unset();
session_destroy();
setcookie("id", "", time() - 3600, "/");
setcookie("username", "", time() - 3600, "/");
$url=$cp_base_url . "login.php";
header("Refresh:0,url=$url");
exit();
?>