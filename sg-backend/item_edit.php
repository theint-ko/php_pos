<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');


$title="Admin Panel:Item Create ";
require('../templates/cp_template_header.php') ;
require('../templates/cp_template_sidebar.php') ;
require('../templates/cp_template_top_nav.php') ;


$error = false;
$error_message ="";
$category_id ="";
$item_name = "";
$code_no = "";

if(isset($_POST['form-sub']) && $_POST['form-sub']==1){
    
    $process_error=false;
    $upload_process=true;
    $id          =$mysqli->real_escape_string($_POST['id']);
    $item_name   =$mysqli->real_escape_string($_POST['name']);
    $category_name =$mysqli->real_escape_string($_POST['category_id']);
    $price       =$mysqli->real_escape_string($_POST['price']);
    $quantity    =$mysqli->real_escape_string($_POST['quantity']);
    // $code_no     =$mysqli->real_escape_string($_POST['code_no']);


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
                $error_message="We Only access image file extension";
        }
            else{
                $upload_path="../asset/upload/item/";
                $unique_name=$without_ext. "_" .date("Ymd_His")."_".uniqid()."." . $extension;

        }
    }

    if($item_name==''){
        $process_error=true;
        $error=true;
        $error_message .='Please Filled Item Name';
    }
    if($category_id==''){
        $process_error=true;
        $error=true;
        $error_message .='Please Choose  Category Id';
        }

    if($price==''){
        $process_error=true;
        $error=true;
        $error_message ='Please Choose  Price';
         }

    if($quantity==''){
        $process_error=true;
        $error=true;
        $error_message .='Please Choose  Quantity';
                }

    $check_cat_sql="SELECT count(id) AS total FROM `item` WHERE name='$item_name' AND id!='$id' AND deleted_at IS
                     NULL";
                $check_cat_result = $mysqli->query($check_cat_sql);
                while($check_cat_row=$check_cat_result->fetch_assoc()){
                $check_cat_total=$check_cat_row['total'];
                }
                if($check_cat_total>0){
                    $process_error = true;
                    $error=true;
                    $error_message="Your Item Name is already exist";
                }

                $today_dt  = date('Y-m-d H:i:s');
                $user_id   = (isset($_SESSION['id'])) ? $_SESSION['id'] : $_COOKIE['id'] ;
                if($process_error == false){

                    $update_sql = "UPDATE `item` SET name='$item_name',
                                    category_id='$category_id',
                                    price='$price',
                                    quantity='$quantity',
                                    image='$unique_name',
                                    updated_at='$today_dt',
                                    updated_by='$user_id',
                                    WHERE id='$id'";

                }else{
                    $update_sql = "UPDATE `item` SET 
                                    name='$item_name',
                                    category_id='$category_id',
                                    price='$price',
                                    quantity='$quantity',
                                    updated_at='$today_dt',
                                    updated_by='$user_id' WHERE id='$id'" ;
                }
                $old_img_sql="SELECT image FROM `category` WHERE id='$id'";
                $old_img_res=$mysqli->query($old_img_sql);
                $old_img_path=$old_img_res->fetch_assoc();
                $old_img=$old_img_path['image'];
                $result = $mysqli->query($update_sql);
                if(!$result){
                    $url=$cp_base_url . "item_list.php?err=create";
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
                        if (file_exists($full_old_img_path)) {
                            unlink($full_old_img_path);
                        }
                        

                        $url=$cp_base_url . "item_list.php?msg=edit";
                        header("Refresh:0,url=$url");
                        exit();
                     }
            
            }

}     


else{
        $id=(int)($_GET['id']);
        $id=$mysqli->real_escape_string($id);
        $sql="SELECT id,name,category_id,price,quantity,code_no,image
            FROM `item` WHERE id='$id' AND deleted_at IS NULL";
        $result=$mysqli->query($sql);
        $res_row=$result->num_rows;
        if($res_row<=0)
        {
        $error=true; 
        $error_message="This item name can't exist" ; 
        }
        else{
        $row=$result->fetch_assoc();
        $item_name=htmlspecialchars($row['name']);
        $category_id=(int)($row['category_id']);
        $price=(int)($row['price']);
        $quantity=(int)($row['quantity']);

        $image=htmlspecialchars($row['image']);
        $image_full_path = $base_url . 'asset/upload/item/' . $id . '/' . $image;
        }
}    


 ?>


            <!-- page content -->
            <div class="right_col" role="main" >
                <div class="">
 
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Item Create </h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                <form class="" action="" method="post" novalidate enctype="multipart/form-data">
    
                                        <span class="section">Item Create </span>
                                        <div class="field item form-group">
                                            <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Category Name<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" id="name"  name="name" required="required" value = "<?php echo $item_name;?>"/>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Select<span class="required">*</span></label>
											<div class="col-md-9 col-sm-9 ">
												<select class="form-control" name="category_id">
													<option value = "">Choose Parent Category </option>
													
                                                    <?php require('../include/include_category.php');
                                                     getParentCategory($mysqli, $parent_id,['item'=>true,'category'=>false]);
                                                     ?>                                                                                                   ?>
												</select>
											</div>
                                        </div>
                                
                                        <div class="field item form-group">
                                            <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Price<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" id="price"  name="price" required="required" value = "<?php echo $price;?>"/>
                                            </div>
                                        </div>
                                        

                                        <div class="field item form-group">
                                            <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Quantity<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" id="quantity"  name="quantity" required="required" value = "<?php echo $quantity;?>"/>
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
                                <input class="hide img-upload" type="file" name="file" onchange="fileSelect(this)" />
                        </div>
                                        <div class="ln_solid">
                                            <div class="form-group">
                                                <div class="col-md-6 offset-md-3">
                                                    <button type='submit' class="btn btn-primary">Submit</button>
                                                    <button type='reset' class="btn btn-success">Reset</button>
                                                    <input type="hidden" name="form-sub" value="1"/>
                                                    <input type="hidden" name="id" value="<?php echo $id;?>">
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
     <!-- <script src="<?php echo $base_url?>asset/js/validator/multifield.js"></script>  -->
    <!-- <script src="<?php echo $base_url?>asset/js/validator/validator.js"></script> -->


    <script>
        // // initialize a validator instance from the "FormValidator" constructor.
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
        // }).prop('checked', false); -->

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
})
 
<?php
    }
    ?>
</script>    
            
     <?php require('../templates/cp_template_html_end.php') ;
            ?>

    