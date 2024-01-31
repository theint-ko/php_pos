<?php
session_start();
require('common/database.php');
require('common/config.php');

?>

<?php

$error=false;
$error_message="";

if(isset($_POST['form-sub'])&& $_POST['form-sub']==1){
    $username=$mysqli->real_escape_string($_POST['username']);
    $password=$_POST['password'];
    // $remember=isset($_POST['remember']) ? $_POST['remember'] : 0;
    if($username=='' || $password==''){
      $error=true;
      $error_message.="Please fill your username or password!";
    }

      $sql="SELECT id,username,password FROM user WHERE username='$username' AND
           role='$cashier_role' AND deleted_at IS NULL";
      $result=$mysqli->query($sql);
      $res_row=$result->num_rows;
      if($res_row <= 0){
        $error= true;
        $error_message="Your name does not exist in our database";
      }else{
        while($row=$result->fetch_assoc()){
          $db_id=(int)$row['id'];
          $db_username=htmlspecialchars($row['username']);
          $db_password=$row['password'];
          $md5_password=md5($sharkey.md5($password));
            if($md5_password==$db_password){
                $_SESSION['id']=$db_id;
                $_SESSION['username']=$db_username;
                // $_SESSION['role']=$db_role;
                $url=$cp_base_url . "index.php";
                header("Refresh:0,url=$url");
                exit();
            }else{
                $error= true;
                $error_message="Your password does not exist in our database";
            }
          
         }
          
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/bootstrap/css/bootstrap.css" />
    <link rel = "stylesheet" href = "<?php echo $base_url;?>asset/css/login.css" />
    <script src="<?php echo $base_url;?>asset/js/angular.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/jquery-2.2.4.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<section class="intro" ng-app="myApp" ng-controller="myCtrl">
    <div class="inner">

        <div class="content">
            <form class="login-form" action="<?php echo $base_url;?>login.php" method="POST" id="myForm">

            <table style="margin:0 auto;width: 18vw;">

                <tr>
                    <td colspan="3">
                        <input type="text" placeholder="Enter Username" class="userInput" id="inputUsername" name="username" ng-focus="usernameFocus()" ng-model="username">
                    </td>
                </tr>

                <tr>
                    <td colspan="3"><input type="password" placeholder="Enter Password" class="userInput" id="inputPassword" name="password" ng-focus="passwordFocus()" ng-model="password"></td>
                </tr>

                <tr>
                    <td><button type="button" class="number-btn fl-left num-btn"  ng-click="numberClick(0)">0</button></td>
                    <td><button type="button" class="number-btn num-btn" ng-click="numberClick(1)">1</button></td>
                    <td><button type="button" class="number-btn fl-right num-btn" ng-click="numberClick(2)">2</button></td>
                </tr>

                <tr>
                    <td><button type="button" class="number-btn fl-left num-btn" ng-click="numberClick(3)">3</button></td>
                    <td><button type="button" class="number-btn num-btn" ng-click="numberClick(4)">4</button></td>
                    <td><button type="button" class="number-btn fl-right num-btn" ng-click="numberClick(5)">5</button></td>
                </tr>

                <tr>
                    <td><button type="button" class="number-btn fl-left num-btn" ng-click="numberClick(6)">6</button></td>
                    <td><button type="button" class="number-btn num-btn" ng-click="numberClick(7)">7</button></td>
                    <td><button type="button" class="number-btn fl-right num-btn" ng-click="numberClick(8)">8</button></td>
                </tr>

                <tr>
                    <td><button type="button" class="number-btn fl-left num-btn" ng-click="numberClick(9)">9</button></td>
                    <td><button type="button" class="number-btn clear-btn" ng-click="delete()">X</button></td>
                    <td><button type="button" class="number-btn fl-right enter-btn" ng-click="Login()">Enter</button></td>
                    <input type="hidden"  name="form-sub" value="1"/>
                </tr>
                </table>
            </form>
        </div>
    </div>
</section>
<script src="<?php echo $base_url;?>asset/js/page/login.js"></script>
</body>
</html>