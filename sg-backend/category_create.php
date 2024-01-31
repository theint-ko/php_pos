<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');

$error = false;
$error_message = '';

$category_name = '';
$parent_id = '';

 if(isset($_POST['form-sub']) && $_POST['form-sub']==1){
    $process_error = false;
    $category_name = $mysqli->real_escape_string($_POST['name']);
    $parent_id     = $mysqli->real_escape_string($_POST['parent_id']);
    // $status        = (int)($_POST['status']);
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    $file = $_FILES['file'];
    if($file['error'] != 0){
        $process_error = true;
        $error = true;
        $error_message .= 'Please upload category image.\n';

    }
    else{
        $upload_file=$file['name'];
        $allow_extension=array('jpg','png','jpeg','gif','svg');
        $explode = explode('.' , $upload_file);
        $image_without_ext=$explode[0];
        $extension=end($explode);
        if(!in_array($extension,$allow_extension)){
            $process_error = true;
            $error = true;
            $error_message = 'We only access image file extension';
        }
        else{
            $upload_path = "../asset/upload/";
            $img_update = $image_without_ext."_".date('Ymd_His')."_".uniqid()."." .$extension;
        }
        
    }
    if($category_name == ''){
        $process_error = true;
        $error = true;
        $error_message .= 'Please choose category name\n';
    }

    if($parent_id == ''){
        $process_error = true;
        $error = true;
        $error_message .= 'Please choose parent category\n';
    }
            $check_cat_sql = "SELECT count(id) as total FROM category WHERE 
                                name ='$category_name' and deleted_at is NULL and deleted_by is NULL";
             $check_cat_res = $mysqli->query($check_cat_sql);
                while($check_cat_row = $check_cat_res->fetch_assoc()){
                    $cat_total = $check_cat_row ['total'];
                }
                if($cat_total>0){
                $process_error = true;
                $error = true;
                $error_message = 'This category name is already exit';
             }
    if($process_error == false){
        $image = $file['name'];
        $today_dt = date('Y-m-d H:i:s');
        $user_id = (isset($_SESSION['id'])? $_SESSION['id'] : $_COOKIE['id']);

        $sql = "INSERT INTO category (name,parent_id,image,created_at,created_by,updated_at,
                                    updated_by) VALUES ('$category_name','$parent_id','$img_update',
                                    '$today_dt','$user_id', '$today_dt','$user_id')";
             $result = $mysqli->query($sql);
             if(!$result){
                $process_error = true;
                $error = true;
                $error_message = 'Oop! Something wrong.Please contact administrator';
                $url=$cp_base_url . "category_listing.php?err=create";
                header("Refresh:0,url=$url");
                exit();
             }
             

             else{
                $inserted_id=$mysqli->insert_id;
                $full_path_dir = $upload_path . $inserted_id;
                $full_path_img = $full_path_dir."/".$img_update;
                if(!file_exists($full_path_dir)){
                    mkdir($full_path_dir,0777,true);
             }
             move_uploaded_file($file['tmp_name'], $full_path_img);
             $inputFile=$full_path_img;
             require('../asset/lib/crop_and_resize_image.php');
             $url=$cp_base_url . "category_listing.php?msg=create";
            header("Refresh:0,url=$url");
            exit();
            }
        }
    }
     
?>
<?php
            $title="Admin Panel::Create Category";
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
                                    <h2>Create Category </h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                <form class="" action="" method="post" novalidate enctype="multipart/form-data">
    

                                        <span class="section">Create Category </span>
                                        <div class="field item form-group">
                                            <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Category Name<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" id="name"  name="name" required="required" value = "<?php echo $category_name;?>"/>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Select<span class="required">*</span></label>
											<div class="col-md-9 col-sm-9 ">
												<select class="form-control" name="parent_id">
													<option value = "">Choose Parent Category </option>
													<option value = "0" <?php if($parent_id == 0) {echo 'selected';}?>>Parent Category</option>
                                                    <?php 
                                                    require('../include/include_category.php');
                                                    getParentCategory($mysqli, $parent_id,['category'=>true,'item'=>false]);
                                                    ?>
												</select>
											</div>
                                        </div>
                                        <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Choose Category Image<span class="required">*</span></label>
											<div class="col-md-9 col-sm-9 ">
                                                <div id="preview_viewer" >
                                                    <div class="vertical_center">
                                                        <label class="choose_image" onclick="fileBrowse()" value="">Choose Photo</label>
                                                        
                                                    </div>
                                                </div>  
                                                <div id="preview_viewer_img" style="display:none;">
                                                    <div class="vertical_center">
                                                       <img src="" id="image-preview" style="width:100%"/><br/> 
                                                        <label class="choose_image" onclick="fileBrowse()">Choose Photo</label>
                                                        
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>


										</div>
                                        <input class="hide img-upload" type="file" name="file"  onchange="SelectFile(this)"/>      
                                        </div>

                                      
                                        <div class="ln_solid">
                                            <div class="form-group">
                                                <div class="col-md-6 offset-md-3">
                                                    <button type='submit' class="btn btn-primary">Submit</button>
                                                    <button type='reset' class="btn btn-success">Reset</button>
                                                    <input type="hidden" name="form-sub" value="1"/>

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
                function fileBrowse(){
                    $('.img-upload').click();
                }

                function SelectFile(input){
                    const file = input.files[0];
                    if(file){
                        var reader=new FileReader();
                        reader.onload = function(e){
                            var imageDataUrl = e.target.result;
                            $('#image-preview').attr('src',imageDataUrl)
                        }
                        reader.readAsDataURL(file);
                        $('#preview_viewer').hide()
                        $('#preview_viewer_img').show()

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
 
<?php
    }
    ?>
</script>    
            
     <?php require('../templates/cp_template_html_end.php') ;
            ?>

    