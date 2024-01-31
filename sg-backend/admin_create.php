<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');

$error = false;
$error_message = '';
$process_error =false;


 if(isset($_POST['form-sub']) && $_POST['form-sub']==1)
 {
    $process_error = false;
    $admin_name=$mysqli->real_escape_string($_POST['name']);
    $password=$mysqli->real_escape_string($_POST['password']);
    $confirm_pass=$mysqli->real_escape_string($_POST['confirm_pass']);
 
 
   
 if($admin_name == ''){
    $process_error = true;
    $error = true;
    $error_message .= 'Please Fill Admin name\n';
}

if($password == ''){
    $process_error = true;
    $error = true;
    $error_message .= 'Please Fill Password\n';
}
  
if($confirm_pass == ''){
    $process_error = true;
    $error = true;
    $error_message .= 'Please Fill Confirm Password\n';
}

if($confirm_pass != $password ){
    $process_error = true;
    $error = true;
    $error_message .= 'Must be same password and confirm password.Try Again!!';
}
 

 if ($confirm_pass == $password && $process_error == false) {
    $today_dt = date("Y-m-d H:i:s");
    $user_id = (isset($_SESSION['id']) ? $_SESSION['id'] : $_COOKIE['id']);
    $md5_password=md5($sharkey.md5($password));        

    $sql = "INSERT INTO user(username,password,role,created_at,created_by,updated_at,updated_by)
            VALUES ('$admin_name','$md5_password','$admin_role','$today_dt','$user_id','$today_dt','$user_id') ";


    $result = $mysqli->query($sql);
    $url=$cp_base_url."admin_list.php?msg=edit";
        header("Refresh:0,url=$url");
        exit();
 } 
 }

?>
<?php
            $title="Admin Panel::Admin Create";
            require('../templates/cp_template_header.php') ;
            require('../templates/cp_template_sidebar.php') ;
            require('../templates/cp_template_top_nav.php') ;
      
            ?>


            <!-- page content -->
            <div class="right_col" role="main" >
                <div class="">
 
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Admin Create</h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                <form class="" action="" method="post" novalidate enctype="multipart/form-data">
    

                                        <span class="section">Admin Create </span>
                                        <div class="field item form-group">
                                            <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Admin Name<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" id="name"  name="name" required="required" value = ""/>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label for="password" class="col-form-label col-md-3 col-sm-3  label-align">Password<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" type="password" id="password"  name="password" required="required"  oninput="sanitizePassword(this)" value = ""/>
                                            </div>
                                        </div>
                        
                                        
                                        <div class="field item form-group">
                                            <label for="confirm-pass" class="col-form-label col-md-3 col-sm-3  label-align"> Confirm Password<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" type="password" id="confirm_pass"  name="confirm_pass" required="required" value = ""/>
                                            </div>
                                        </div>


                                      
                                        <div class="ln_solid">
                                            <div class="form-group">
                                                <div class="col-md-6 offset-md-3">
                                                    <button type='submit' class="btn btn-primary">Submit</button>
                                                    <button type='reset' class="btn btn-success">Reset</button>
                                                    <input type="hidden" name="form-sub" value="1" />

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->
            <?php require('../templates/cp_template_footer_start.php') ;
            ?>
        </div>
    </div>

    <script src="<?php echo $base_url?>asset/js/jquery1.9/jquery.1.9.min.js"></script>

    </script>
     <?php require('../templates/cp_template_footer_end.php') ;
            ?>

<script>
function sanitizePassword(input) {
    // Remove non-numeric characters
    let numericOnly = input.value.replace(/[^0-9]/g, '');
    
    // Limit length to 8 characters
    numericOnly = numericOnly.substring(0, 8);

    // Update the input value
    input.value = numericOnly;


}
</script>
           

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
 
<?php
    }
    ?>
</script>    
 
     <?php require('../templates/cp_template_html_end.php') ;
            ?>

    