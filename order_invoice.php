<?php
session_start();
require('common/database.php');
require('common/config.php');
require('common/check_cashier_authentication.php');
$title = "View Details";
require('templates/template_header.php');

$id =(int)($_GET['id']);
$id =$mysqli->real_escape_string($id);

?>

<div class="header-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-4 heightLine_01 head-lbox">
                <div>
                    <a class="btn btn-large dash-btn"
                        href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dashboard</a>
                </div>
            </div>
            <div class="col-md-2 col-2 heightLine_01">
                <img src="<?php echo $base_url; ?>asset/images/resturant_logo.png" alt="ROS logo" class="ros-logo">
            </div>

            <div class="col-md-3 col-3 heightLine_01 head-rbox">
                <div>
                    <span class="staff-name">
                        Staff
                    </span>
                    <div class="dropdown show pull-right">
                        <button role="button" id="dropdownMenuLink" class="btn btn-primary user-btn"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo $base_url; ?>asset/images/login_img.png" alt="login image">
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="">Logout</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div><!-- header-sec -->
<div class="container" ng-app="myApp" ng-controller="myCtrl" ng-init="init(<?php echo $id; ?>)" style="margin-top: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <div id="order-detail"
                    style="width: 350px; height: auto; margin: 0 auto; border: 1px solid black; box-sizing: border-box;">
                    <div class="receipt_header"
                        style="padding-bottom: 30px; border-bottom: 1px dashed #000; text-align: center;">
                        <h2 style="font-size: 20px; margin-bottom: 10px; margin-top: 20px;">Receipt of Sale <span
                                style="display: block; font-size: 20px;">SG</span>
                        </h2>
                        <h2 style="font-size: 14px; color: #727070; font-weight: 300;">Address: SoftGuide, 575B <span
                                style="display: block;">Tel:
                                +1 012 345 67 89</span></h2>
                        <div class="date" style="text-align: left;">&nbsp;Date : {{order.date}}</div>
                        <div class="name" style="text-align: left;">&nbsp;Cashier Name :Hnin Si14280</div>
                        <div class="name" style="text-align: left;">&nbsp;OrderNo: {{order.order_no}}</span>

                    </div>

                    <div class="receipt_body" style="margin-top: 25px;">
                        
                        <div class="items" style="margin-top: 25px;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="border-top: 1px dashed #000; padding-top: 10px; margin-top: 10px; text-align: center;">
                                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">ITEM
                                    </th>
                                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">QTY</th>
                                    <th style="padding: 15px; text-align: right; border-bottom: 1px solid #ddd;">PRICE
                                    </th>
                                </thead>
                                <tbody>
                                    <tr style="text-align: left; border-bottom: 1px solid #ddd;"
                                        ng-repeat="detail in order.order_detail">
                                        <td style="padding: 15px;">{{detail.name}}</td>
                                        <td style="padding: 15px;">{{detail.quantity}}</td>
                                        <td style="padding: 15px; text-align: right;">{{detail.sub_total}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr><td></td>
                                        <td style="padding: 15px; text-align: left;"><b>Total</b></td>
                                        <td colspan="2" style="padding: 15px; text-align: right;">{{order.total}}</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style="padding: 15px; text-align: left;"><b>Payment</b></td>
                                        <td colspan="2" style="padding: 15px; text-align: right;">{{order.payment}}</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style="padding: 15px; text-align: left;"><b>Refund</b></td>
                                        <td colspan="2" style="padding: 15px; text-align: right;">{{order.refund}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <p style="border-top: 1px dashed #000; padding-top: 10px; margin-top: 5px;margin-bottom:3px; text-align: center;">
                        Thank You for Shopping With Our Mark!</p>
                </div>
               
            </div>
        </div>
    </div>
    <div class="footer text-center" style="margin-top: 15px;">
    <button class="btn btn-success"><a class="btn" href="order_list.php">Back</button>
                 <img src="<?php echo $base_url;?>asset/images/payment/previous_img.png" alt="Previous" class="heightLine_06">
                </a>&nbsp;
    <button class="btn btn-primary" onclick="printInvoice()" >Print</button>

        <img src="<?php echo $base_url; ?>asset/images/softguide_logo.png" style="max-width: 100%;">
    </div>
    
</div>


<script src="<?php echo $base_url; ?>asset/js/page/order_invoice.js"></script>

<script>
function printInvoice() {
   
    var  printContents    = document.getElementById('order-detail').innerHTML;
    var  originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
    

<?php
require('templates/template_footer.php');
?>