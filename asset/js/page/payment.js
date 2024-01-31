var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope, $http) {
    $scope.order = [];
    $scope.kyats = [];
    $scope.selectIndex = [];
    $scope.payment_quantity = '';
    $scope.balance  = '';
    $scope.disabled = false
    $scope.payment_disabled=true
    $scope.base_url = base_url;

    $scope.init = function(id) {
        var data = {
            id: id
        };

        var url = base_url + 'api/order_invoice_test.php';

        $http({
            method: 'POST',
            url: url,
            data: data
        }).then(function(response) {
            if (response.status == 200) {
                $scope.order = response.data[0];
                $scope.balance= $scope.order.total;
            } else {
                alert("Something wrong");
            }
        }, function(error) {
            console.error(error);
        });
    };

    $scope.showCash = function(value) {
        const cash = parseInt(value);
        const index = $scope.kyats.length + 1;
        const quantity =($scope.payment_quantity == '') ? 1 : $scope.payment_quantity;
        const total_cash = cash * quantity;
        const data = {cash: cash, index: index, quantity:quantity,total_cash:total_cash};
        $scope.kyats.push(data);
        $scope.clear();
        $scope.calculateBalance();

        // $scope.selectCash(index);
    };

    $scope.selectCash = function(index) {
        var selectedIndex = $scope.selectIndex.indexOf(index);
        if (selectedIndex !== -1) {
            $scope.selectIndex.splice(selectedIndex, 1);
        } else {
            $scope.selectIndex.push(index);
        }
        $scope.calculateBalance();
    };

    $scope.voidCancel = function() {
        $scope.kyats = $scope.kyats.filter(function(kyat) {
            return $scope.selectIndex.indexOf(kyat.index) === -1;
        });
        $scope.selectIndex = [];
        for(let i = 0;i < $scope.kyats.length;i++){
            const index = i+1
            $scope.kyats[i].index=index;
        }
        $scope.calculateBalance();
    };


    $scope.quantity = function(qty) {
        $scope.payment_quantity += parseInt(qty);
    };

    $scope.clear = function() {
        $scope.payment_quantity = '';
    };

    $scope.calculateBalance=function(){
        let total_cash_res=0;
        for(i=0 ; i<$scope.kyats.length;i++){
            const cash_res=$scope.kyats[i].total_cash
            total_cash_res = parseInt(total_cash_res)+parseInt(cash_res)
        }

    if(total_cash_res>=$scope.order.total){
        $scope.payment_disabled=false
        $scope.disabled =true
        $scope.refund = parseInt(total_cash_res) -parseInt($scope.order.total)
    }
    else{
        $scope.payment_disabled=true
        $scope.disabled = false
        $scope.refund=''
    }
    // $scope.refund = (total_cash_res>=$scope.order.total) ? parseInt(total_cash_res) -parseInt($scope.order.total):''
    $scope.balance=$scope.order.total - total_cash_res;
    $scope.balance=($scope.balance<0) ? 0 :$scope.balance
    }
    $scope.toPay=function(){
        console.log($scope.order,$scope.kyat,$scope.refund)
        let total_cus_pay=0
        for(i=0 ; i<$scope.kyats.length;i++){
            const cash_res=$scope.kyats[i].total_cash
            total_cus_pay = parseInt(total_cus_pay)+parseInt(cash_res)
        }

        var data = {
            id: $scope.order.id,
            total_cus_pay:total_cus_pay,
            refund: $scope.refund,
            kyats:$scope.kyats
        };

        var url = base_url + 'api/payment_store.php';

        $http({
            method: 'POST',
            url: url,
            data: data
        }).then(function(response) {
            if (response.status == 200) {
                // $scope.order = response.data[0];
                 $scope.balance= $scope.order.total;
            } else {
                alert("Something wrong");
            }
        }, function(error) {
            console.error(error);
        });
    }
});
