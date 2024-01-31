<?php
session_start();
require('../common/config.php');
require('../common/database.php');
require('../common/checkauthentication.php');


$confirm = false;
$error     = false;
$error_msg = "";
$success   = false;
$success_msg = "";
if(isset($_GET['msg'])){
$success = true;
switch ($_GET['msg']){
case 'create':
  $success_msg = "Category create success";
  break;
case 'edit':
  $success_msg = "Category update success";
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
$sql = "SELECT c.id, c.name, c.category_id, c.price, c.quantity,c.code_no,c.image,
        p.name AS category_name
        FROM item c
        LEFT JOIN category p ON c.category_id = p.id
        WHERE c.deleted_at IS NULL ORDER BY c.id DESC";

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
                            <th class="column-title">Category Name</th>
                            <th class="column-title">Price</th>
                            <th class="column-title">Quantity </th>
                            <th class="column-title">Code No</th>
                            <th class="column-title">Image</th>
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
                                   
                                $id            = (int)($row['id']);    
                                $name          = htmlspecialchars($row['name']);
                                $category_name = htmlspecialchars($row['category_name']);
                                $price         = (int)($row['price']);
                                $quantity      = (int)($row['quantity']);
                                $code_no         = htmlspecialchars($row['code_no']);
                                $image         = htmlspecialchars($row['image']);
                                $full_path_img = $base_url . '/asset/upload/item/'. $id .'/'.$image;
                              
                                $edit_link     = $cp_base_url .'item_edit.php?id=' . $id;
                                $delete_link   = $cp_base_url .'item_delete.php?id=' . $id;
                            ?>
                             
                            <tr class="even pointer">
                            <td class="a-center ">
                              <input type="checkbox" class="flat" name="table_records">
                            </td>
                            
                                    <td><?php echo $name;?></td>
                                    <td><?php echo $category_name;?></td>
                                    <td><?php echo $price;?></td>
                                    <td><?php echo $quantity;?></td>
                                    <td><?php echo $code_no;?></td> 

                                    <td> 
                                    <div  style= "width:100px;height:80px;overflow:hidden;">
                                      <img src ="<?php echo $full_path_img;?>" style="width:100px;height:50px;"/> 
                                      </div>
                                </td>
        
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
     
     <?php require('../templates/cp_template_html_end.php') ;
      ?>
    