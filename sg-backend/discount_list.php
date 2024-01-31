<?php
session_start();
require('../common/config.php');
require('../common/database.php');
require('../common/checkauthentication.php');
require('../include/include_function.php');

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
      $success_msg = "Discount create success";
      break;
    case 'edit':
      $success_msg = "Discount update success";
      break;
    case 'delete':
      $success_msg = "Discount delete success";    
      break;  

    }
  }
  
    if(isset($_GET['err'])){
      $error = true;
    switch ($_GET['err']){
      case 'create':
      $error_msg  = "Discount Create fail";
      break;
      case 'edit':
      $error_msg = "Discount Update fail";
      break;


    }

}
  $sql="SELECT id, name, start_date, end_date, status,
       CASE
      WHEN amount IS NULL THEN CONCAT(percentage, '%')
      ELSE CONCAT(amount, 'Kyats')
      END AS calculated_value
      FROM `discount_promotion`
      WHERE deleted_at IS NULL
      ORDER BY id DESC";
  $result =$mysqli->query($sql);  

$title="Admin Panel::Discount List Form";
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
                           
                            <th class="column-title">Discount Name </th>
                            <th class="column-title">Discount Amount</th>
                            <th class="column-title">Start Date</th>
                            <th class="column-title">End Date</th>
                            <th class="column-title">Items </th>
                            <th class="column-title">Status</th>
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
                                $name          = htmlspecialchars($row['name']);
                                $amount        = htmlspecialchars($row['calculated_value']);
                                $start_date    =formatDateJFY($row['start_date']);
                                $end_date      = formatDateJFY($row['end_date']);                            
                                $status        = (int) $row['status'];
                                $item_selected ="SELECT T02.name FROM discount_item T01 LEFT JOIN item T02 ON
                                T01.item_id=T02.id WHERE T01.discount_id='$id' AND T01.deleted_at IS NULL AND
                                T02.deleted_at IS NULL";
                                $item_selected_res = $mysqli->query($item_selected);
                                $edit_link     = $cp_base_url .'discount_edit.php?id=' . $id;
                                $delete_link   = $cp_base_url .'discount_delete.php?id=' . $id;
                                $dis_item='';
                                while($item_selected_row=$item_selected_res->fetch_assoc()){
                                  $dis_item.= $item_selected_row['name'].',';

                                }
                                $dis_item = rtrim($dis_item,',');
                                ?>
                            <tr class="even pointer">
                            <td class="a-center">
                              <input type="checkbox" class="flat" name="table_records">
                            </td>
                            
                            <td class=" "><?php echo $name;?></td>
                            <td class=" "><?php echo $amount;?></td>
                            <td class=" "><?php echo $start_date;?></td>
                            <td class=" "><?php echo $end_date;?></td>
                            <td class=" "><?php echo $dis_item;?></td>

                            <td class=" "><?php
                                          if($status==0){
                          
                                             echo '<span class="badge badge-primary">Enable</span>';
                                          }
                                          else {
                                      
                                           echo  '<span class="badge badge-secondary">Disable</span>';
                                         
                                          }
                                          ?>
                        
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
    