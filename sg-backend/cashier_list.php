<?php
session_start();
require('../common/config.php');
require('../common/database.php');
require('../common/checkauthentication.php');
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
      $success_msg = "Cashier create success";
      break;
    case 'edit':
      $success_msg = "Cashier update success";
      break;
    case 'delete':
      $success_msg = "Cashier delete success";    
      break;  

    }
  }
  
    if(isset($_GET['err'])){
      $error = true;
    switch ($_GET['err']){
      case 'create':
      $error_msg  = "Cashier Create fail";
      break;
      case 'edit':
      $error_msg = "Cashier Update fail";
      break;
      case  'shift':
      $error_msg = "You cannot delete while shift is opening";
      break;

    }

  }
 
  $sql = "SELECT id,username,password FROM user WHERE deleted_at is NULL and deleted_by is NULL";
  $result = $mysqli->query($sql);
  
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
                <h3>Tables</h3>
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
                           
                            <th class="column-title">Name </th>
                            <th class="column-title">Password</th>
                        
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="7">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                           <?php

                                while($row = $result->fetch_assoc()){
                               
                                $id            = (int) $row['id'];    
                                $name          = htmlspecialchars($row['username']);
                                $password       = htmlspecialchars($row['password']);
                                $edit_link     = $cp_base_url .'admin_edit.php?id=' . $id;
                                $delete_link   = $cp_base_url .'admin_delete.php?id=' . $id;
                                ?>
                            <tr class="even pointer">
                            <td class="a-center ">
                              <input type="checkbox" class="flat" name="table_records">
                            </td>
                            
                            <td class=" "><?php echo $name;?></td>
                            <td class=" "><?php echo $password;?></td>
                            

                                <!-- <td class="a-right a-right"></td> -->
                              <td class=" last">
                              <a href="<?php echo $edit_link;?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>&nbsp;&nbsp;
                              <a href="<?php echo $delete_link;?>" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>&nbsp;&nbsp;
                              <a href="javascript:void(0)" class="btn btn-primary btn-xs"><i class="fa fa-arrows"></i>Move</a>
                            
                            </td>
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
    