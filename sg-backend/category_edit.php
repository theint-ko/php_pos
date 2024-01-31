<?php 
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');


$title="Admin Panel::Update Category";
require('../templates/cp_template_header.php') ;
require('../templates/cp_template_sidebar.php') ;
require('../templates/cp_template_top_nav.php') ;
$error=false;
$error_message='';
$parent_id='';
$form=true;
    if(isset($_POST['form-sub']) && $_POST['form-sub']==1){
        $process_error=false;
        $upload_process=true;
        $id=$mysqli->real_escape_string($_POST['id']);
        $category_name=$mysqli->real_escape_string($_POST['name']);
        $parent_id=$mysqli->real_escape_string($_POST['parent_id']);
        $status =$mysqli->real_escape_string($_POST['status']);
        $file=$_FILES['file'];
        if($file['error']!=0){
            $upload_process=false;
        }
        else{
            $upload_file=$file['name'];
            $allow_extension=array('jpg','jpeg','svg','png','gif');
            $explode=explode('.',$upload_file);
            $without_ext=$explode['0'];
            $extension=end($explode);
                if(!in_array($extension, $allow_extension)){
                    $process_error=true;
                    $error=true;
                    $error_message .='We Only access image file extension';
            }
                else{
                    $upload_path ="../asset/upload/";
                    $unique_name=$without_ext. "_" .date("Ymd_His")."_".uniqid()."." . $extension;

            }
        }

        if($category_name==''){
            $process_error=true;
            $error=true;
            $error_message='Please Filled Category Name';
        }
        if($parent_id==''){
            $process_error=true;
            $error=true;
            $error_message='Please Choose Parent  id';
            }

            $check_cat_sql="SELECT count(id) AS total FROM `category` WHERE name='$category_name' AND id!='$id' AND deleted_at IS
                            NULL";
            $check_cat_result = $mysqli->query($check_cat_sql);
                while($check_cat_row=$check_cat_result->fetch_assoc()){
                    $check_cat_total=$check_cat_row['total'];
                }
                    if($check_cat_total>0){
                        $process_error=true;
                        $error=true;
                        $error_message='Your Category Name is already exist';
                    }
                    if($process_error==false){
                        $today_dt=date("Y-m-d H:i:s");
                        $user_id=(isset($_SESSION['id'])? $_SESSION['id'] : $_COOKIE['id']);
                        $image=$file['name'];
                        if($upload_process){
                            $update_sql = "UPDATE `category` SET 
                                            name='$category_name', 
                                            parent_id='$parent_id',
                                            image='$unique_name',
                                            status='$status',
                                            updated_at='$today_dt',
                                            updated_by='$user_id' WHERE id='$id'" ;

                        }
                        else{
                            $update_sql = "UPDATE `category` SET 
                                            name='$category_name',
                                            parent_id='$parent_id',
                                            status='$status',
                                            updated_at='$today_dt',
                                            updated_by='$user_id' WHERE id='$id'" ;
                        }
                        $old_img_sql="SELECT image FROM `category` WHERE id='$id'";
                        $old_img_res=$mysqli->query($old_img_sql);
                        $old_img_path=$old_img_res->fetch_assoc();
                        $old_img=$old_img_path['image'];
                        $result = $mysqli->query($update_sql);
                        if(!$result){
                            $error = true;
                            $error_message .= 'Oop! Something Wrong.\n';
                            $url = $cp_base_url . "category_listing.php?err=edit";
                            header("Refresh:0,url=$url");
                            exit();
                        }
                        else{
                            if($upload_process){
                            $full_path_dir=$upload_path . $id;
                            $full_path_img=$full_path_dir."/".$unique_name;
                            if (!file_exists($full_path_dir)) {
                                mkdir($full_path_dir, 0777, true);
                            }
                        
                            move_uploaded_file($file['tmp_name'],$full_path_img);
                            
                            $inputFile = $full_path_img;
                            require('../asset/lib/crop_and_resize_image.php');
                            $full_old_img_path=$full_path_dir . '/' . $old_img;
                            unlink($full_old_img_path);

                            $url=$cp_base_url . "category_listing.php?msg=edit";
                            header("Refresh:0,url=$url");
                            exit();
                         }
  
                        // else{
                        //  $url=$cp_base_url . "category_listing.php?msg=edit";
                        //  header("Refresh:0,url=$url");
                        //  exit();
                        // }
                             
                    }
                }
            }
            

    else{           
                $id=(int)($_GET['id']);
                $id=$mysqli->real_escape_string($id);
                $sql="SELECT id,name,parent_id,image,status FROM `category` WHERE id='$id' AND deleted_at IS NULL";
                $result1=$mysqli->query($sql);
                $res_row=$result1->num_rows;
                if($res_row<=0)
                { 
                $form=false;
                $error=true; 
                $error_message="This category name can't exist" ; 
                }
                else{
                $row=$result1->fetch_assoc();
                $category_name=htmlspecialchars($row['name']);
                $parent_id=(int)($row['parent_id']);
                $status = (int)($row['status']);
                $image=htmlspecialchars($row['image']);
                $image_full_path = $base_url . 'asset/upload/' . $id . '/' . $image;

        }
    }

?>


<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Update Category </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php 
                            if($form == true){
                        ?>
                        <form class="" action="<?php echo $cp_base_url; ?>category_edit.php" method="post"
                            enctype="multipart/form-data" novalidate>

                            <span class="section">Category Edit </span>
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Category
                                    Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" id="name" data-validate-length-range="6"
                                        data-validate-words="2" name="name" required="required"
                                        value="<?php echo $category_name; ?>" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Select<span
                                        class="required">*</span></label>
                                <div class="col-md-9 col-sm-9 ">
                                    <select class="form-control" name="parent_id">
                                        <option value="">Choose Parent Category</option>
                                        <option value="0" <?php if($parent_id==0){echo "selected";}?>>Parent
                                            Category</option>
                                        <?php 
                                        require('../include/include_category.php');
                                        getParentCategory($mysqli, $parent_id,['category'=>true,'item'=>false]);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Choose Category
                                    Image<span class="required">*</span></label>
                                <div class="col-md-9 col-sm-9 ">
                                    <div id="preview_viewer" style="display:none">
                                        <div class="vertical_center">
                                            <label class="choose_image" onclick="fileBrowse()">Choose Photo</label>
                                        </div>
                                    </div>
                                    <div id="preview_viewer_img">
                                        <div class="vertical_center">
                                            <img src="<?php echo $image_full_path;?>" id="preview_image"
                                                style="width:100%;" /></br>
                                            <label class="choose_image" onclick="fileBrowse()">Choose Photo</label>
                                        </div>
                                    </div>
                                </div>
                                <input class="hide img-upload" type="file" name="file" onchange="fileSelect(this)" />
                            </div>


                                         <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Status<span class="required">*</span></label>
											    <div class="col-md-9 col-sm-9 ">
												<select class="form-control" name="status">
													<option value = "0"<?php if($status==0){echo "selected";}?> > Enable</option>
													<option value = "1"<?php if($status==1){echo "selected";}?> >Disable</option>
                                                 
												</select>
											</div>
                                        </div>

                            <div class="ln_solid">
                                <div class="form-group">
                                    <div class="col-md-6 offset-md-3">
                                        <button type='submit'class="btn btn-primary">Submit</button>
                                        <button type='reset' class="btn btn-success">Reset</button>
                                        <input type="hidden" value="1" name="form-sub" />
                                        <input type="hidden" value="<?php echo $id;?>" name="id">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php 
                            }else{
                            ?>

                        <a href="<?php echo $cp_base_url; ?>category_listing.php" class="btn btn-info btn-xs"><i
                                class="fa fa-reply"></i>
                            Goback </a>
                        <?php } 
                        ?>
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
<script src="<?php echo $base_url?>asset/js/validator/multifield.js"></script>
<!-- <script src="<?php echo $base_url?>asset/js/validator/validator.js"></script> -->


<!-- <script> -->
// initialize a validator instance from the "FormValidator" constructor.
// // A "<form>" element is optionally passed as an argument, but is not a must
// var validator = new FormValidator({
//     "events": ['blur', 'input', 'change']
// }, document.forms[0]);
// // on form "submit" event
// document.forms[0].onsubmit = function(e) {
//     var submit = true,
//         validatorResult = validator.checkAll(this);
//     console.log(validatorResult);
//     return !!validatorResult.valid;
// };
// // on form "reset" event
// document.forms[0].onreset = function(e) {
//     validator.reset();
// };
// // stuff related ONLY for this demo page:
// $('.toggleValidationTooltips').change(function() {
//     validator.settings.alerts = !this.checked;
//     if (this.checked)
//         $('form .alert').remove();
// }).prop('checked', false);

<?php require('../templates/cp_template_footer_end.php') ;
            ?>
<script>
function fileBrowse() {
    $('.img-upload').click();
}

function fileSelect(input) {
    const allow_extensions = ['jpeg', 'jpg', 'png', 'svg', 'gif'];
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const image_data_url = e.target.result;
            $("#preview_image").attr("src", image_data_url);
        };
        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (allow_extensions.includes(fileExtension)) {
            reader.readAsDataURL(file);
            $('#preview_viewer').hide();
            $('#preview_viewer_img').show();
        } else {
            alert('Invalid file extension. Please select a valid image file.');

        }
    }
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
</script>
<?php
    }
    ?>


<?php require('../templates/cp_template_html_end.php') ;
            ?>