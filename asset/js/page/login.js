var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope) {
    $scope.focus_input =''
    $scope.username = ''
    $scope.password = ''
    $scope.Login = function() {
    console.log($scope.username, 'username',$scope.focus_input);
  }
    $scope.usernameFocus =function(){
        $scope.focus_input = 'username'
    }
    
    $scope.passwordFocus =function(){
        $scope.focus_input = 'password'
    }
    
    $scope.numberClick = function(number){
        var input_num = parseInt(number)
        if( $scope.focus_input  == '' || $scope.focus_input == 'username'){
            $scope.username = $scope.username + input_num 
        } else{
            $scope.password = $scope.password + input_num
        }
    }

    $scope.delete =function(){

       if($scope.focus_input == 'username'){
        const username=$scope.username.slice(0,-1);
        $scope.username = username
       }
       else{

        const password =$scope.password.slice(0,-1);
        $scope.password =password
       }
        
    }

    $scope.Login = function(){
        if($scope.username == '' || $scope.password == ''){
            alert("Please Fill username and password")
            
        }else{
            const form=document.getElementById("myForm")
            form.submit()
        }
        
    }


});
