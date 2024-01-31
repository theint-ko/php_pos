<?php
session_start();
require('common/database.php');
require('common/config.php');
require('common/check_cashier_authentication.php');
$title="Order List";
require('templates/template_header.php');
require('include/include_function.php');

?>

     <style>
    table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    }

    .canceled-order {
        text-decoration: line-through;
    }

    th, td {
    padding: 8px;
    text-align: left;
    border: 1px solid #86bc25; /* Set collapsed border for every td */
    }

    th {
    background-color: #4CAF50;
    color: white;
    }

    tr:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.12);
    }
    </style>
    
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
            </div><!-- header-sec -->
        <div class="container" ng-app="myApp" ng-controller="myCtrl" ng-init="init()">
            <div class="row">
                <div class="col-md-3">
                    <h3 class="h3"><strong>Order Listing</strong></h3>
                </div>
                <div class="col-lg-8 col-md-7 col-sm-6 col-6 receipt-btn">
                <a class="btn" href="order.php">
                        <img src="<?php echo $base_url;?>asset/images/payment/previous_img.png" alt="Previous" class="heightLine_06">
                    </a>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-11">
                        <table id="invoice">
                            <thead>
                              <tr>
                                <th>Order No</th>
                                <th>Order Time</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr ng-repeat="order in orders">
                                <td ng-class="{ 'canceled-order': order.status == '2' }">{{order.order_no}}
                                    <span class="badge badge-secondary" ng-if="order.status=='0'">Unpaid</span>
                                    <span class="badge badge-primary"  ng-if="order.status=='1'">Paid</span>
                                    <span class="badge badge-danger"   ng-if="order.status=='2'">Cancel</span>
                                </td>
                                <td>{{order.created_at}}</td>
                                <td>{{order.total}}Kyats</td>
                                <td>
                                <a class="btn btn-primary" ng-if="order.status=='0'" href="{{base_url}}payment.php?id={{order.id}}">
                                    <i class="fa fa-money"></i> To Pay
                                </a>&nbsp;

                                <button class="btn btn-warning" ng-if="order.status=='2'" ng-click="orderChangeStatus(order.id,0)">
                                    Active
                                </button>

                                <button class="btn btn-danger" ng-if="order.status=='0'" ng-click="orderChangeStatus(order.id,2)">
                                    <i class="fa fa-trash"></i> Cancel
                                </button>

                                
                                <a  class="btn btn-success"  href="{{base_url}}order_invoice.php?id={{order.id}}" class="btn btn-success">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                

                            <a class="btn btn-primary" ng-if="order.status=='0'" href="{{base_url}}order_edit.php?id={{order.id}}">
                                <i class="fa fa-pencil"></i> Edit
                            </a>&nbsp;
                        </td>
                              </tr>
                            </tbody>
                    </table>
                </div>
        </div>
</div>

<script src="<?php echo $base_url;?>asset/js/page/order_list.js"></script>

<?php require('templates/template_footer.php');
?>
