
<?php
session_start();
require('common/database.php');
require('common/config.php');
require('common/check_cashier_authentication.php');
$title="Home Page";
require('templates/template_header.php');
require('include/include_function.php');

?>
        
        <div class="header-sec">
            <div class="container" >
                <div class="row">
                    <div class="col-md-4 col-4 heightLine_01 head-lbox">
                        <div>
                            <a class="btn btn-large dash-btn"
                                href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dashboard</a>
                        </div>
                    </div>
                    <div class="col-md-2 col-2 heightLine_01">
                        <img src="<?php echo $base_url;?>asset/images/resturant_logo.png" alt="ROS logo" class="ros-logo">
                    </div>

                    <div class="col-md-3 col-3 heightLine_01 head-rbox">
                        <div>
                            <span class="staff-name">
                                Staff
                            </span>
                            <div class="dropdown show pull-right">
                                <button role="button" id="dropdownMenuLink" class="btn btn-primary user-btn"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?php echo $base_url;?>asset/images/login_img.png" alt="login image">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
             <div class="container">  
             <div class="row cmn-ttl cmn-ttl1">
            <div class="container"> 
                <h3>Make Order</h3>
            </div> 
        </div>
        <div class="row">   
            <div class="col-md-3">
                <a class="text-center" href="order.php"><img id="img" class="bottom image" src= "asset/images/make-order.png" ></a>
            </div>

            <div class="col-md-3">
                <a class="text-center" href="order_list.php"><img id="img" class="bottom image" src= "asset/images/order-list.png" ></a>
            </div>
        </div><!-- End Row -->
    </div><!-- container-fluid -->
    <div class="footer text-center">  
        <img src="asset/images/softguide_logo.png" />
    </div><!-- footer -->
</div><!-- wrapper -->

</body>
</html>