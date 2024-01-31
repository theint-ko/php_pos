<?php
session_start();
require('common/database.php');
require('common/config.php');
$title = "Order Payment";
require('templates/template_header.php');
require('common/check_cashier_authentication.php');
$id =(int)($_GET['id']);
$id =$mysqli->real_escape_string($id);
?>
    </style>
        <div class="header-sec">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-4 heightLine_01 head-lbox">
                        <div>
                            <a class="btn btn-large dash-btn" href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dashboard</a>
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
                                <button role="button" id="dropdownMenuLink" class="btn btn-primary user-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?php echo $base_url;?>asset/images/login_img.png" alt="login image">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="login.php">Logout</a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div><!-- header-sec -->
    <div class="wrapper"> 
        <div class="container-fluid receipt" ng-app="myApp"  ng-controller="myCtrl" ng-init="init(<?php echo $id; ?>)">  
          <div class="row cmn-ttl cmn-ttl2">
              <div class="container">
                <div class="row">
                    <input type="hidden" class="void-value" id="" />
                    <input type="hidden" class="void-type" id="" />
                    <div class="col-lg-4 col-md-5 col-sm-6 col-6">
                        <h3>Order no : {{order.order_no}}
                        </h3>
                    </div>
                  <div class="col-lg-8 col-md-7 col-sm-6 col-6 receipt-btn">
                        <button class="btn print-modal" id="printInvoice" ><a href="{{base_url}}order_invoice.php?id={{order.id}}" >
                            <img src="<?php echo $base_url;?>asset/images/payment/print_img.png" alt="Print Image" class="heightLine_06">
                        </button>

                    <a class="btn" href="order_list.php">
                        <img src="<?php echo $base_url;?>asset/images/payment/previous_img.png" alt="Previous" class="heightLine_06">
                    </a>
                  </div>
                </div> 
              </div> 
          </div>
            <div class="row"> 
                <div class="container"> 
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-6">
                            <div class="table-responsive">
                                <table class="table receipt-table">
                                    <tr>
                                        <td>Sub Total</td>
                                        <td>{{order.total}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-gray">Name</td>
                                        <td class="bg-gray">Quantity</td>
                                        <td class="bg-gray">Price</td>
                                    </tr> 

                                    <tr ng-repeat="detail in order.order_detail">
                                        <td>{{detail.name}} </td>
                                        <td>{{detail.quantity}}</td>
                                        <td class="text-right">{{detail.sub_total}}</td>
                                    </tr>

                
                                </table>
                            </div><!-- table-responsive -->

                            <h3 class="receipt-ttl">TOTAL - {{order.total}}</h3>
                            <div class="table-responsive">
                                <table class="table receipt-table" id="invoice-table">
                                    <tr class="before-tr" style="height: 32px;">
                                        <td colspan="2" class="bl-data"></td>
                                    </tr>
                                    <tr class="tender" ng-repeat="kyat in kyats" ng-class="{ 'bg-gray-cash': selectIndex.indexOf(kyat.index) !== -1 }">
                                      <td></td>
                                      <td class="pointer" ng-click="selectCash(kyat.index)">{{ kyat.total_cash }} mmk</td>
                                  </tr>

                              <tr>
                              <td>BALANCE</td>
                              <td class="balance">{{balance}}</td>
                            </tr>
                            <tr>
                              <td>REFAND</td>
                              <td class="change">
                                  {{refund}}
                              </td>
                            </tr>
                          </table>
                        </div><!-- table-responsive -->
                          <div class="row receipt-btn02">
                              <div class="col-md-6 col-sm-6 col-6">
                                  <button class="btn btn-primary item-modal" data-toggle="modal" data-target="#printModal" ><a href="{{base_url}}order_invoice.php?id={{order.id}}">VIEW DETAILS</button>
                              </div>
                          </div>

                </div> 
                <div class="col-md-8 col-sm-8 col-6">
                  <div class="row"> 
                    <div class="col-md-12 list-group" id="myList" role="tablist">
                        <a class="list-group-item list-group-item-action heightLine_05 active" data-toggle="list" href="#home" role="tab" id="payment-cash">
                          <span class="receipt-type cash-img"></span><span class="receipt-txt">Cash</span>
                        </a>
                        <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list" href="#profile" role="tab" id="payment-card">
                          <span class="receipt-type card-img"></span><span class="receipt-txt">Card</span>
                        </a>
                        <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list" href="#messages" role="tab" id="payment-voucher">
                          <span class="receipt-type voucher-img"></span><span class="receipt-txt">Voucher</span>
                        </a>
                        <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list" href="#settings" role="tab" id="payment-nocollection">
                          <span class="receipt-type collection-img"></span><span class="receipt-txt">No Collection</span>
                        </a>
                        <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list" href="#settings" role="tab" id="payment-loyalty">
                          <span class="receipt-type loyality-img"></span><span class="receipt-txt">Loyalty</span>
                        </a>
                    </div> <!-- list-group -->
                    <div class="col-md-12">
                    <div class="tab-content row">
                      <div class="tab-pane active" id="home" role="tabpanel">
                        <button class="btn heightLine_04 cash-payment" ng-disabled="disabled" id="CASH"><span class="extra-cash"></span><span>Kyats</span></button>
                        <button class="btn heightLine_04 cash-payment"  ng-disabled="disabled" id="CASH50" ng-click="showCash(50)"><span class="money">50</span> <span>Kyats</span></button>
                        <button class="btn heightLine_04 cash-payment"  ng-disabled="disabled" id="CASH100" ng-click="showCash(100)"><span class="money">100</span><span>Kyats</span></button>
                        <button class="btn heightLine_04 cash-payment" ng-disabled="disabled" id="CASH200"  ng-click="showCash(200)"><span class="money">200</span><span>Kyats</span></button>
                        <button class="btn heightLine_04 cash-payment" ng-disabled="disabled" id="CASH500"  ng-click="showCash(500)"> <span class="money">500</span> <span>Kyats</span></button>
                        <button class="btn heightLine_04 cash-payment" ng-disabled="disabled" id="CASH1000" ng-click="showCash(1000)"><span class="money">1000</span><span>Kyats</span></button>
                        <button class="btn heightLine_04 cash-payment" ng-disabled="disabled" id="CASH5000" ng-click="showCash(5000)"><span class="money">5000</span><span>Kyats</span> </button>
                        <button class="btn heightLine_04 cash-payment" ng-disabled="disabled" id="CASH10000" ng-click="showCash(10000)"> <span class="money">10000</span><span>Kyats</span></button>
                      </div>
                      <div class="tab-pane" id="profile" role="tabpanel">
                            <button class="btn heightLine_05 mpu-type agd-mpu card-payment" id="MPU_AGD"><span class="receipt-type cash-img"></span><span class="receipt-txt">AGD</span></button>
                            <button class="btn heightLine_05 mpu-type kbz-mpu card-payment" id="MPU_KBZ"><span class="receipt-type cash-img"></span><span class="receipt-txt">KBZ</span></button>
                            <button class="btn heightLine_05 mpu-type uab-mpu card-payment" id="MPU_UAB"><span class="receipt-type cash-img"></span><span class="receipt-txt">UAB</span></button>
                            <button class="btn heightLine_05 mpu-type mob-mpu card-payment" id="MPU_MOB"><span class="receipt-type cash-img"></span><span class="receipt-txt">MOB</span></button>
                            <button class="btn heightLine_05 mpu-type chd-mpu card-payment" id="MPU_CHD"><span class="receipt-type cash-img"></span><span class="receipt-txt">CHD</span></button>

                            <button class="btn heightLine_05 mpu-type kbz-visa card-payment" id="VISA_KBZ"><span class="receipt-type cash-img"></span><span class="receipt-txt">KBZ</span></button>
                            <button class="btn heightLine_05 mpu-type cb-visa card-payment" id="VISA_CB"><span class="receipt-type cash-img"></span><span class="receipt-txt">CB</span></button>
                      </div>
                    </div>
                    </div>
                    <div class="payment-cal col-md-12"> 
                      <div class="row"> 
                        <div class="col-md-12 payment-show">
                          <p class="amount-quantity" style="min-height: 33px;">{{payment_quantity}}</p>
                        </div>
                        <div class="col-md-12 receipt-btn3"> 
                          <button class="btn quantity" id="1"  ng-click="quantity(1)">1</button>
                          <button class="btn quantity" id="2"  ng-click="quantity(2)">2</button>
                          <button class="btn quantity" id="3" ng-click="quantity(3)">3</button>
                          <button class="btn quantity" id="4" ng-click="quantity(4)">4</button>
                          <button class="btn quantity" id="4"  ng-click="quantity(5)">5</button>
                          <button class="btn quantity" id="6"  ng-click="quantity(6)">6</button>
                          <button class="btn quantity" id="7"  ng-click="quantity(7)">7</button>
                          <button class="btn quantity" id="8"  ng-click="quantity(8)">8</button>
                          <button class="btn quantity" id="9" ng-click="quantity(9)">9</button>
                          <button class="btn quantity" id="0"  ng-click="quantity(0)">0</button>
                        </div>
                        <div class="col-md-12 receipt-btn4">                       
                            <button class="btn btn-primary void-btn" id = 'void-item' ng-click="voidCancel()">VOID <i class="fa fa-trash-alt"></i></button>
                            <button class="btn clear-input-btn" ng-click="clear()">CLEAR INPUT</button>
                            <button class="btn btn-primary foc-btn" ng-disabled="payment_disabled" ng-click="toPay()"><a href="order_list.php">To Pay</button>
                        </div>
                      </div>

                    </div>
                  </div> <!-- row -->     
                </div> <!-- col-md-8 -->

              </div>

            </div> 
          </div>
        </div><!-- container-fluid -->
      </div>

<div class="footer text-center">  
            <img src="<?php echo $base_url;?>asset/images/softguide_logo.png" alt="Softguide logo">
        </div><!-- footer -->
    </div><!-- wrapper -->
    <script src="<?php echo $base_url;?>asset/js/page/payment.js"></script>

    <?php require('templates/template_footer.php');
?>