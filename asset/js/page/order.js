var app = angular.module('myApp', []);

app.controller('myCtrl', function ($scope, $http) {
    $scope.showCategory = true
    $scope.showItem     =false
    $scope.categories   = []
    $scope.items        =[]
    $scope.itemsData    =[]
    $scope.allItems=[]
    $scope.base_url     = base_url
    $scope.sub_total    = 0

    $scope.init = function () {
        $scope.fetchCategoryByParent(0)
        $scope.fetchAllItems()
    }
       
    $scope.getChildCatagories = function(id){
        $scope.categories = []
        $scope.fetchCategoryByParent(id)
    }

    $scope.getParentCategory = function(){
        $scope.fetchCategoryByParent(0) 
    }

    $scope.fetchCategoryByParent = function(parent_id){
        $scope.showCategory = true
        $scope.showItem     =false
        var data = {
            // key and value
            parent_id:parent_id
            
        }
        var url = base_url + 'api/get_category.php'

    $http({
        method: 'POST',
        url: url,
        data: data
    }).then(function (response) {
        if(response.status  == 200){
            if(response.data.length <= 0){
                $scope.showCategory = false
                $scope.showItem     = true
                $scope.fetchItems(parent_id)

            }else{
                $scope.categories = response.data
            }
           
        }else{
            alert("Something wrong")
        }
    },function(error){
        console.error(error);
    });

    }

    $scope.searchItems=function(){
        if($scope.search_item == ''){
       $scope.fetchCategoryByParent(0)
       $scope.showCategory = true
       $scope.showItem     =false
       
   }else{
           $scope.items=[]
           $scope.showCategory = false
           $scope.showItem     =true
           $scope.items=$scope.allItems.filter(function(item){
            return item.code && item.code.toString().startsWith($scope.search_item)
           })
   }
}


        $scope.fetchItems = function(category_id) {
                var data = {
                category_id: category_id
                };

        var url = base_url + 'api/get_items.php';

            $http({
                    method: 'POST',
                    url: url,
                    data: data
            }).then(function(response) {
                if (response.status == 200) {
                        $scope.items = response.data;
                    } else {
                        alert("Something wrong");
                    }
                    }, function(error) {
                    console.error(error);
                    });
                    };



    $scope.getItem = function(item_id) {
        var data = {
            item_id: item_id
        };
    
        var url = base_url + 'api/get_item.php';
    
        $http({
            method: 'POST',
            url: url,
            data: data
        }).then(function(response) {
            if (response.status == 200) {
                let addItem = false;
                let quantityToAdd = 1;
    
                // Use map to iterate over itemsData and modify the quantity of the matching item
                $scope.itemsData = $scope.itemsData.map(item => {
                    if (item.id === item_id) {
                        quantityToAdd = item.quantity + 1;
                        addItem = true;
                        return { ...item, quantity: quantityToAdd }; // Create a new object with updated quantity
                    }
                    return item;
                });  
                if (!addItem) {
                    $scope.itemsData.push(response.data[0]);
                }
                $scope.calculateSubtotal()
            } else {
                alert("Something wrong");
            }
        }, function(error) {
            console.error(error);
        });
    };
    

    $scope.cancelItem = function(item_id){
        
        $scope.itemsData =$scope.itemsData.filter(item=>item.id !=item_id)
        $scope.calculateSubtotal()
        
    }

    $scope.qtyPlus = function(item_id) {

        var index = $scope.itemsData.findIndex(item => item.id === item_id);
    
        if (index !== -1) {
            $scope.itemsData[index].quantity += 1;
            $scope.calculateSubtotal()
        }
    };
    
    $scope.qtyMinus = function(item_id) {
        var index = $scope.itemsData.findIndex(item => item.id === item_id);
    
        if (index !== -1) {
    
            $scope.itemsData[index].quantity -= 1;
            $scope.calculateSubtotal()
        }
    };

    $scope.calculateSubtotal= function(){
        
        $scope.sub_total =0
        for( var i=0;i<$scope.itemsData.length;i++){
            $scope.sub_total +=$scope.itemsData[i].amount * $scope.itemsData[i].quantity;
        }
    }
    $scope.fetchAllItems = function(){
        var data = {}
        var url = base_url + 'api/get_all_items.php'
    
        $http({
            method: 'POST',
            url: url,
            data: data
        }).then(function (response) {
            if(response.status  == 200){
                $scope.allItems =response.data
               
            }else{
                alert("Something wrong")
            }
        },function(error){
            console.error(error);
        });
    
        }
       
            $scope.searchItems=function(){
                if($scope.search_item == ''){
                    $scope.fetchCategoryByParent(0)
                    $scope.showCategory = true
                    $scope.showItem     =false
                    
                }else{
                $scope.items=[]
                $scope.showCategory = false
                $scope.showItem     =true
          
                $scope.items=$scope.allItems.filter(function(item){
                 return item.code && item.code.toString().startsWith($scope.search_item)
                })
        }
        }

        $scope.orderItems = function(){
            var orderDetails = {
                items:$scope.itemsData,
                sub_total:$scope.sub_total,
                shift_id:shift_id
            }       

        var url = base_url + 'api/item_holder.php';

            $http({
                    method: 'POST',
                    url: url,
                    data: orderDetails
                    
            }).then(function(response) {
                if (response.status == 200) {
                       alert("Successfully Inserted")

                    } else {
                        alert("Something wrong");
                    }
                    }, function(error) {
                    console.error(error);
                    });

        }
});
