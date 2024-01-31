<?php
session_start();
require('common/database.php');
require('common/config.php');
require('common/check_cashier_authentication.php');
$title="Order Edit";
require('templates/template_header.php');
$id =(int)($_GET['id']);
$id =$mysqli->real_escape_string($id);

$order_check_status="SELECT count(id) AS total FROM `order` 
                    WHERE id ='$id' AND status='0'";

$order_check_result=$mysqli->query($order_check_status);
while($order_check_row=$order_check_result->fetch_assoc()){
    $order_status=$order_check_row['total'];

}
if($order_status<=0){
    $url=$cp_base_url . "order-list";
    header("Refresh:0,url=$url");
    exit();
}
?>
    <style>
        .item-td {
            text-align:center !important;
        }
        .price-input {
            width: 100% !important;
            text-align:center;
        }
        .clediv {
            clear:both;
        }
    </style>

        <div class="container-fluid receipt category-pg" ng-app="myApp" ng-controller="myCtrl" ng-init="init('<?php echo $id?>')">
            <div class="row cmn-ttl cmn-ttl2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-5 col-sm-6 col-6">
                            <h3>
                                Category
                            </h3>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-6 col-6 receipt-btn">
                            <button class="btn" ng-click="returnBack()">
                              <img src="<?php echo $base_url;?>asset/images/payment/previous_img.png" alt="Previous" class="heightLine_06" />
                            </button>
                         </div>
                    </div>
                </div>
            </div>
            <div class="item-container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="cat-table">
                            <div class="table-responsive">
                                <button class="scroll-txt cat-to-btm2"><i class="fas fa-angle-double-down"></i></button>
                                <form class="form-horizontal" id="order-form">
                                    <table class="table table-hover item-list" style="text-align:left;">
                                        <thead>
                                            <tr>
                                                <th width="20%" align="center">Item Name</th>
                                                <th width="20%" align="center">Quantity</th>
                                                <th width="10%" align="center">Price</th>
                                                <th width="10%" align="center">Discount</th>
                                                <th width="10%" align="center">Amount</th>
                                                <th width="15%" align="center">Item Code</th>
                                                <th width="15%" align="center">Cancel</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cat-table-body">
                                            <tr class="item-tr" ng-repeat="data in itemsData">
                                                <td class="item-td" width="20%"><p>{{data.name}}</p></td>
                                                <td class="cart_quantity item-td" width="20%">
                                                    <div class="qty-box">
                                                        <input type='button' value='-' class='qtyminus' field='quantity' ng-click="qtyMinus(data.id)" >
                                                        <input type='text'  value='{{data.quantity}}' class='qty' />
                                                        <input type='button' value='+' class='qtyplus' field='quantity' ng-click="qtyPlus(data.id)" />
                                                    </div>
                                                </td>
                                                <td class="item-td" width="10%">{{data.price}}</td>
                                                <td id="discount" class="item-td">
                                                    <span>{{data.discount*data.quantity}}</span></td>
                                                <td class="item-td" width="10%">
                                                    <input type="text" value="{{(data.price-data.discount)*data.quantity}}" style="border:none;background:none;" readonly class="price-input" />
                                                </td>
                                                <td class="item-td" width="15%">
                                                    <span>{{data.code}}</span>
                                                   
                                                        <!-- <input type="checkbox" value="1" name="" />
                                                        <div class="check-mark"></div>
                                                    </label> -->
                                                </td>

                                                <td class="item-td" width="15%">
                                                    <button class="cancel-btn" type="button" ng-click="cancelItem(data.id)">Cancel</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                                <button class="scroll-txt cat-to-top2" type="button"><i class="fas fa-angle-double-up"></i></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="price-table">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" rowspan="5" class="order-btn-gp">
                                                <button class="order-btn makeorder-btn" id="back-lg" >
                                                    <img src="<?php echo $base_url;?>asset/images/payment/previous_img.png" alt="Previous" class="heightLine_06">     
                                                </button>
                                                <button class="order-btn makeorder-btn" id="order-item" ng-click="orderItems('<?php echo $id;?>')">
                                                    <img src="<?php echo $base_url;?>asset/images/payment/order.png" class="heightLine_06">
                                                </button>
                                            </td>
                                            <td>Sub Total :  </td>
                                            <td>{{sub_total}}  </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- col-md-9 -->

                    <div class="col-md-3">
                        <div class="row category">
                            <div class="col-md-12 cat-list" id="cathome">
                                <div class="cat-ttl" >
                                    <button class="backBtn" id="" ng-click="getParentCategory()"><i class="fas fa-angle-left"></i></button>
                                    <input type="text" class="search-bar" placeholder="Search..." ng-model="search_item" ng-change="searchItems()">
                                </div>

                                <div class="tab-content row" id="cat-tab-content">
                                    <button class="scroll-txt cat-to-btm"><i class="fas fa-angle-double-down"></i></button>
                                    <div class="tab-pane active clearfix" id="categoryDiv" role="tabpanel">
                                        <!-- Category Loop Start Here -->
                                        <div class="cat-box" style="width: 45%"  ng-repeat="category in categories" ng-if="showCategory">
                                            <button ng-click="getChildCatagories(category.id)">
                                                <figure>
                                                    <img ng-src="{{base_url}}asset/upload/{{category.id}}/{{category.image}}" class="img-responsive">
                                                    <figcaption>{{category.name}}</figcaption>
                                                </figure>
                                            </button>
                                        </div>
                                        <!-- Category Loop End Here -->
                                        <div class="cat-box"  ng-if="showItem" ng-repeat="item in items">
                                            <button ng-click="getItem(item.id)">
                                                <figure>
                                                    <img ng-src="{{base_url}}asset/upload/item/{{item.id}}/{{item.image}}" class="img-responsive">
                                                    <figcaption>{{item.name}}</figcaption>
                                                </figure>
                                            </button>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="setDiv" role="tabpanel">
                                    </div>
                                    <button class="scroll-txt cat-to-top"><i class="fas fa-angle-double-up"></i></button>
                                </div> <!-- tab-content -->
                            </div>
                        </div> <!-- row -->
                    </div> <!-- col-md-3 -->
                </div><!-- row -->
            </div>
        </div><!-- container-fluid -->
    </div><!-- wrapper -->
    <script src="<?php echo $base_url;?>asset/js/page/order_edit.js"></script>

<?php require('templates/template_footer.php');
?>