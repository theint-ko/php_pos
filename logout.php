<?php
session_start();
require('common/config.php');
session_unset();
session_destroy();
setcookie("cid","",time() - 3600);
setcookie("username","",time() - 3600,"/");
$url = $base_url."login";
        header("Refresh:0,url=$url");
        exit();

        ?>
