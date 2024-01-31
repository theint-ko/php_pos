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
    $id = $mysqli->real_escape_string($_POST['id']);
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


$check_cat_sql="SELECT count(id) AS total FROM `user` WHERE name='$admin_name' AND id!='$id' AND deleted_at IS
NULL";
$check_cat_result = $mysqli->query($check_cat_sql);
while($check_cat_row=$check_cat_result->fetch_assoc()){
$check_cat_total=$check_cat_row['total'];
}
if($check_cat_total>0){
$process_error=true;
$error=true;
$error_message='Admin Name is already exist';
}
 

 if ($confirm_pass == $password && $process_error == false) {
    $today_dt = date("Y-m-d H:i:s");
    $user_id = (isset($_SESSION['id']) ? $_SESSION['id'] : $_COOKIE['id']);
    $md5_password=md5($sharkey.md5($password));        

        $update_sql = "UPDATE `user` SET 
                        name='$admin_name', 
                        updated_at='$today_dt',
                        updated_by='$user_id' WHERE id='$id'" ;


                    $url=$cp_base_url."admin_list.php?msg=edit";
                    header("Refresh:0,url=$url");
                    exit();
    }

 } else{

    $id=(int)($_GET['id']);
    $id=$mysqli->real_escape_string($id);
    $sql="SELECT id,username,password FROM `user` WHERE id='$id' AND deleted_at IS NULL";
    $result1=$mysqli->query($sql);
    $res_row=$result1->num_rows;
    if($res_row<=0)
    { 
    
    $error=true; 
    $error_message="This admin name can't exist" ; 
    }
    else{
    $row=$result1->fetch_assoc();
    $admin_name=htmlspecialchars($row['username']);
   
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
    

                                        <span class="section">Admin Edit</span>
                                        <div class="field item form-group">
                                            <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Admin Name<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" id="name"  name="name" required="required" value = ""/>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label for="password" class="col-form-label col-md-3 col-sm-3  label-align">Password<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" type="password" id="password"  name="password" required="required" value = ""/>
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
                                                    <input type="hidden" value="<?php echo $id;?>" name="id">
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

    