angular.module('calculatorApp', [])
  .controller('CalculatorController', function ($scope) {
    $scope.expression = '';

    $scope.appendToExpression = function (value) {
      $scope.expression += value;
    };

    $scope.clear = function () {
      $scope.expression = '';
    };

    $scope.calculate = function () {
      try {
        $scope.expression = eval($scope.expression).toString();
      } catch (error) {
        $scope.expression = 'Error';
      }
    };
  });
