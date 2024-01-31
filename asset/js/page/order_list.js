var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope,$http) {
    $scope.orders=[]
    $scope.base_url=base_url
    $scope.init =function(){
    var data = {
        shift_id: shift_id
        }

var url = base_url + 'api/get_orders.php'

    $http({
            method: 'POST',
            url: url,
            data: data
    }).then(function(response) {
        if (response.status == 200) {
            //console.log(response.data);
            $scope.orders = response.data
            } else {
                alert("Something wrong")
            }
            }, function(error) {
            console.error(error)
            });
    }

    $scope.orderChangeStatus = function(id,status){
        var data = {
            id: id ,
            status:status
            }
    
    var url = base_url + 'api/order_change_status.php'
    
        $http({
                method: 'POST',
                url: url,
                data: data
        }).then(function(response) {
            if (response.data.status == 200) {
                $scope.init()
                } else {
                    alert("Something wrong")
                }
                }, function(error) {
                console.error(error)
                });
        }
});
