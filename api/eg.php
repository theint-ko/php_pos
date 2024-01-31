<?php
$title = 'Order List Form';
require('../require/common.php');
require('../template/template_header.php');
require('../include/include_function.php');
$id=(int)$_GET['id'];
$currentDate = date("Ymd");
$date=convertDateFormatDmY($currentDate);
$time=convertCurrentTimeToRangoonFormat();
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .container {
        display: block;
        width: 100%;
        background: #fff;
        max-width: 270px;
        padding: 25px;
        margin: 50px auto 0;
        box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }

    .receipt_header {
        padding-bottom: 40px;
        border-bottom: 1px dashed #000;
        text-align: center;
    }

    .receipt_header h1 {
        font-size: 20px;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .receipt_header h1 span {
        display: block;
        font-size: 25px;
    }

    .receipt_header h2 {
        font-size: 14px;
        color: #727070;
        font-weight: 300;
    }

    .receipt_header h2 span {
        display: block;
    }

    .receipt_body {
        margin-top: 25px;
    }

    table {
        width: 100%;
    }

    thead,
    tfoot {
        position: relative;
    }

    thead th:not(:last-child) {
        text-align: left;
    }

    thead th:last-child {
        text-align: right;
    }

    thead::after {
        content: '';
        width: 100%;
        border-bottom: 1px dashed #000;
        display: block;
        position: absolute;
    }

    tbody td:not(:last-child),
    tfoot td:not(:last-child) {
        text-align: left;
    }

    tbody td:last-child,
    tfoot td:last-child {
        text-align: right;
    }

    tbody tr:first-child td {
        padding-top: 15px;
    }

    tbody tr:last-child td {
        padding-bottom: 15px;
    }

    tfoot tr:first-child td {
        padding-top: 15px;
    }

    tfoot::before {
        content: '';
        width: 100%;
        border-top: 1px dashed #000;
        display: block;
        position: absolute;
    }

    tfoot tr:first-child td:first-child,
    tfoot tr:first-child td:last-child {
        font-weight: bold;
        font-size: 20px;
    }

    .date_time_con {
        display: flex;
        justify-content: center;
        column-gap: 25px;
    }

    .items {
        margin-top: 25px;
    }

    h3 {
        border-top: 1px dashed #000;
        padding-top: 10px;
        margin-top: 25px;
        text-align: center;
        text-transform: uppercase;
    }
</style>
<div class="containder_fluid" ng-app="myApp" ng-controller="myCtrl" ng-init="orderDetail(<?php echo $id; ?>)">

    <div class="container">
        <div class="receipt_header">
            <h1>Receipt of Sale <span>SG</span></h1>
            <h2>Address: SoftGuide, 575B <span>Tel: +1 012 345 67 89</span></h2>
            <span>OrderNo: {{orderDetail[0].order_no}}</span>
        </div>

        <div class="receipt_body">
            <div class="date_time_con">
                <div class="date"><?php echo $date; ?></div>
                <div class="time"><?php echo $time; ?></div>
            </div>
            <div class="items">
                <table>

                    <thead>
                        <th>ITEM</th>
                        <th>QTY</th>
                        <th>PRICE</th>
                        <th>DIS</th>
                        <th>AMT</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in orderDetail">
                            
                            <td>{{item.item_name}}</td>
                            <td>{{item.quantity}}</td>
                            <td>{{item.price}}</td>
                            <td>{{item.discount_price}}</td>
                            <td>{{item.sub_total}}</td>
                        </tr>                      
                    </tbody>

                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td>
                                {{qty}}
                            </td>
                            <td></td>
                            <td></td>
                            <td>{{orderDetail[0].total_amount}}</td>
                        </tr>

                        <tr>
                            <td>Cash</td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>Change</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
        <h3>Thank You!</h3>

    </div>

</div>
<script src="<?php echo $base_url; ?>asset/js/page/order.js?v=20240108"></script>
<?php require('../template/template_footer.php'); ?>