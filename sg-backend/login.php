<?php
session_start();
require('../common/database.php');
require('../common/config.php');
// require('../common/checkauthentication.php');

$error=false;
$error_message="";
$username="";

if(isset($_POST['form-sub'])&& $_POST['form-sub']==1){
  $username=$mysqli->real_escape_string($_POST['username']);
  $password=$_POST['password'];
  $remember=isset($_POST['remember']) ? $_POST['remember'] : 0;
  if($username=='' || $password==''){
    $error=true;
    $error_message.="Please fill your username or password!";
  }
  else{
    $sql="SELECT id,username,password,role FROM user WHERE username='$username' AND status='$admin_disable_status' AND deleted_at IS NULL AND deleted_by IS NULL";
    $result=$mysqli->query($sql);
    $res_row=$result->num_rows;
    if($res_row > 0){
      while($row=$result->fetch_assoc()){
        $db_id=(int)$row['id'];
        $db_username=htmlspecialchars($row['username']);
        $db_password=$row['password'];

        $md5_password=md5($sharkey.md5($password));
        $db_role=(int)($row['role']);
        if($md5_password==$db_password){
          if($db_role==$admin_role){
            if($remember==1){
                $cookie_name='id';
                $cookie_value=$db_id;
                setcookie($cookie_name, $cookie_value, time() +(86400*30) , "/");
                $cookie_name='username';
                $cookie_value=$db_username;
                setcookie($cookie_name, $cookie_value, time() + (86400*30), "/");

                // $cookie_name='role';
                // $cookie_value=$db_role;
                // setcookie($cookie_name, $cookie_value, time() +(86400*30) , "/");
                $url=$cp_base_url . "form.php";
                header("Refresh:0,url=$url");
                exit();
            }
            else{
              $_SESSION['id']=$db_id;
              $_SESSION['username']=$db_username;
              // $_SESSION['role']=$db_role;
              $url=$cp_base_url . "form.php";
              header("Refresh:0,url=$url");
              exit();
            }
          }
          else{
            $error=true;
            $error_message.="Your don't have permission to use!";
          }

        }
        else{
            $error=true;
            $error_message.="The password you entered is wrong!";
        }

      }
    }else{
      $error=true;
      $error_message.="The username you entered is wrong!";
    }

  }

    
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela! | </title>

    <!-- Bootstrap -->
    <link href="<?php echo $base_url; ?>asset/css/boostrap/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo $base_url; ?>asset/css/font-awesome/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <script src="<?php echo $base_url;?>asset/js/sweet-alert/sweet-alert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/css/sweet-alert/sweet-alert.css">
 

    <!-- Custom Theme Style -->
    <link href="<?php echo $base_url; ?>asset/css/custom/custom.css?v20231211" rel="stylesheet">
  </head>

  <body class="login">

    <div>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="<?php echo $cp_base_url;?>login.php" method="POST">
              <h1>Login Form</h1>
              <div>
              <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $username;?>" autocomplete="off" />
              </div>
              <div>
              <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" />
              </div>
              <div>             
                <input type="checkbox" checked="" name="remember" value="1" id="rem"> <label for="rem">&nbsp;Remember me&nbsp;</label>
                <button type="submit" name="submit">Login</button>
                <input type="hidden" name="form-sub" value="1" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                

                <div>
                  <p>©223 All Rights Reserved. </p>
                </div>
              </div>
            </form>
          </section>
        </div>

        
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
  <?php
    if($error==true){
      ?>
       <script>
    swal({
  title: "Error!",
  text: "<?php echo $error_message;?>",
  type: "error",
  confirmButtonText: "Close"
});

  </script>
<?php
    }
    ?>

</html>
