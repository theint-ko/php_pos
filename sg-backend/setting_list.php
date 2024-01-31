<?php
session_start();
require('../common/config.php');
require('../common/database.php');
require('../common/checkauthentication.php');
$title="Setting List";
require('../templates/cp_template_header.php');
?>
<?php

    $confirm = false;
    $error     = false;
    $error_msg = "";
    $success   = false;
    $success_msg = "";
    if(isset($_GET['msg'])){
    $success = true;
    switch ($_GET['msg']){
    case 'create':
      $success_msg = "Setting create success";
      break;
    case 'edit':
      $success_msg = "Setting update success";
      break;
    case 'delete':
      $success_msg = "Category delete success";    
      break;  

    }
  }
  
    if(isset($_GET['err'])){
      $error = true;
    switch ($_GET['err']){
      case 'create':
      $error_msg  = "Category Create fail";
      break;
      case 'edit':
      $error_msg = "Category Update fail";
      break;
      case  'shift':
      $error_msg = "You cannot delete while shift is opening";
      break;

    }

  }

$sql = "SELECT id,name,phone,email,address,image FROM setting";
$result    = $mysqli->query($sql);
$title="Admin Panel::Create Form";
require('../templates/cp_template_header.php') ;
require('../templates/cp_template_sidebar.php') ;
require('../templates/cp_template_top_nav.php') ;
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Setting</h3>
              </div>

              <div class="title_right">

              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row" style="display: block;">

              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Table design <small>Custom design</small></h2>

                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">

            

                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th>
                              <input type="checkbox" id="check-all" class="flat">
                            </th>
                           
                            <th class="column-title">Company Name </th>
                            <th class="column-title">Phone No:</th>
                            <th class="column-title">Email</th>
                            <th class="column-title">Address </th>
                            <th class="column-title">Logo </th>
                            <th class="bulk-actions" colspan="7">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                           <?php

                                while($row = $result->fetch_assoc()){
                               
                                $id            = (int) $row['id'];    
                                $name          = htmlspecialchars($row['name']);
                                $phone         = htmlspecialchars($row['phone']);
                                $email         = htmlspecialchars($row['email']);
                                $address       =htmlspecialchars( $row['address']);
                                $image         = htmlspecialchars($row['image']);
                                $full_path_img = $base_url . '/asset/upload/setting/'. $id .'/'.$image;
                    
                                ?>
                            <tr class="even pointer">
                            <td class="a-center ">
                              <input type="checkbox" class="flat" name="table_records">
                            </td>
                            
                            <td class=" "><?php echo $name;?></td>
                            <td class=" "><?php echo $phone;?></td>
                            <td class=" "><?php echo $email;?></td>
                            <td class=" "><?php echo $address;?></td>
                            <td>
                              <div  style= "width:100px;height:80px;overflow:hidden;">
                                <img  src ="<?php echo $full_path_img;?>" style="width:100px;height:50px;"/> 
                                </div>
                                </td>
                                <!-- <td class="a-right a-right"></td> -->
                          </tr>
                         <?php
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>
							
						
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

<?php require('../templates/cp_template_footer_end.php') ;
   ?>

<?php
 if($success == true){
          ?>

        <script>
              new PNotify({	
                          title: 'Success',
                          text: '<?php echo $success_msg;?>',
                          type: 'success',
                          styling: 'bootstrap3'
                          });
             </script>    
          <?php
            }
            ?>
<?php 
    if($error == true){
    ?>
    
<script>
new PNotify({
              title: 'Oh No!',
              text: '<?php echo $error_msg;?>.',
              type: 'error',
              styling: 'bootstrap3'
          });
 </script>   
 <?php
    }
    ?>      
     
     <?php require('../templates/cp_template_html_end.php') ;
      ?>
    