var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope,$http) {
    $scope.order=[]
    //$scope.base_url=base_url
     $scope.init =function(id){
    var data = {
        id: id,
        shift_id:shift_id
        }

var url = base_url + 'api/order_invoice_test.php'

    $http({
            method: 'POST',
            url: url,
            data: data
    }).then(function(response) {
        if (response.status == 200) {
            //console.log(response.data);
            $scope.order = response.data[0]
            } else {
                alert("Something wrong")
            }
            }, function(error) {
            console.error(error)
            });
     }

})