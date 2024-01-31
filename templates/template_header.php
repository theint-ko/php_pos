
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" acontent="">

    <title><?php echo $title;?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/bootstrap/css/fontawesomeall.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/css/font-awesome/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/css/swiper.min.css" />
    <link href="<?php echo $base_url;?>asset/css/sweet-alert.css" rel="stylesheet">

    <script src="<?php echo $base_url;?>asset/bootstrap/js/jquery-2.2.4.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/heightLine.js"></script>
    <script src="<?php echo $base_url;?>asset/js/sweetalert-dev.js"></script>
    <script src="<?php echo $base_url;?>asset/js/swiper.min.js"></script>
    <script src="<?php echo $base_url;?>asset/js/angular.min.js"></script>
    <!--
    <script src="{{ URL::asset('<?php echo $base_url;?>asset/cashier/js/common.js') }}"></script>
    <script src="{{ URL::asset('<?php echo $base_url;?>asset/js/common.js') }}"></script>
-->

        
<script> 
        const base_url = "http://localhost/sg-pos/";
        var shift_id="<?php echo $_SESSION['shift_id'];?>";
</script>
</head>
<body>
    <div class="wrapper">