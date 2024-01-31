<?php
session_start();
require('../common/config.php');
require('../common/database.php');
require('../common/checkauthentication.php');
$confrim=false;
$sql = "SELECT id,started_date_time,end_date_time,status FROM `shift` 
       WHERE deleted_at is NULL AND status='0' ORDER BY id DESC";
     
$result   = $mysqli->query($sql);
$num_res = $result->num_rows; 
$shift_check_sql = "SELECT count(id) AS total,id FROM `shift` WHERE started_date_time is not NULL AND end_date_time is NULL";
$shift_check_result = $mysqli->query($shift_check_sql);

while($shift_check_row = $shift_check_result->fetch_assoc()){
    $shift_check_rows  = $shift_check_row['total'];
}

if($shift_check_rows>0){
  $confrim=true;
  // $shift_id=$shift_check_rows['id'];

}


            $title="Admin Panel:Shift form";
            require('../templates/cp_template_header.php') ;
            require('../templates/cp_template_sidebar.php') ;
            require('../templates/cp_template_top_nav.php') ;
            ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
            
          <a style="display:<?php if($confrim){ echo 'none;';}?>" href="<?php echo $cp_base_url;?>shift_start.php" class="btn btn-primary" role="button" >
              <span class="glyphicon glyphicon-time" aria-hidden="true">&nbsp;Started_Date_Time</span>
          </a>
          <a style="display:<?php if(!$confrim){ echo 'none;';}?>" href="<?php echo $cp_base_url;?>shift_end.php" class="btn btn-danger" role="button">
              <span class="glyphicon glyphicon-off" aria-hidden="true">&nbsp;End_Date_Time</span>
          </a>
      </div>
        <div class="title_right">
        </div>
        </div>
            <div class="clearfix"></div>

            <div class="row" style="display: block;">
              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>You can see the start and end time.</h2>

                    <div class="clearfix"></div>
                  </div>

                   <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <th class="column-title"><span class="glyphicon glyphicon-time" aria-hidden="true">&nbsp;Open Date Time</span></th>
                            <th class="column-title"><span class="glyphicon glyphicon-off" aria-hidden="true">&nbsp;End Date Time</span></th>
                            <th class ="column-title"><span >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  
                    <?php
                    if($num_res>0){
                        while($row=$result->fetch_assoc()){
                        $shift_start_time =$row['started_date_time'];
                        $shift_end_time   =$row['end_date_time'];
                        $view_order_link  =$cp_base_url."order_view.php?start=".$shift_start_time."&end=".$shift_end_time;
                    ?>
                      <tr>
                      <td><?php echo $shift_start_time;?></td>
                      <td><?php echo $shift_end_time;?></td>
                      <td>
                      <a href="<?php echo $view_order_link;?>" class="btn btn-info btn-xs"><i class="fa fa-eye">View Order</i></a>
                      </td>
                      </tr>
                      <?php
                      }
                    }
                      ?>

                        </tr>
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
<script>
  <?php
    function showConfirmation() {
      ?>
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to start?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, start it!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.value) {
                // If the user clicks 'Yes', navigate to the specified URL
                window.location.href = "<?php echo $cp_base_url;?>shift_start.php";
            }
        });
      <?php
    }
    ?>
</script>   
     <?php require('../templates/cp_template_html_end.php') ;
            ?>
    