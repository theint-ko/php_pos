<?php
require('common/database.php');
require('common/config.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Simple AngularJS Calculator</title>
  <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>asset/bootstrap/css/bootstrap.css" />
    <link rel = "stylesheet" href = "<?php echo $base_url;?>asset/css/login.css" />
    <script src="<?php echo $base_url;?>asset/js/angular.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/jquery-2.2.4.min.js"></script>
    <script src="<?php echo $base_url;?>asset/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<section class="intro" ng-app="calculatorApp" ng-controller="CalculatorController">
  <div class="inner">
    <div class="content">

        <h2 style="color: white;">Simple Calculator</h2>

        <table style="margin:0 auto;width: 18vw;">
          <tr>
            <td colspan="4">
              <input type="text" ng-model="expression" id="display" readonly>
            </td>
          </tr>
          <tr>
            <td><button class="userInput" ng-click="appendToExpression(1)">1</button></td>
            <td><button class="userInput" ng-click="appendToExpression(2)">2</button></td>
            <td><button class="userInput" ng-click="appendToExpression(3)">3</button></td>
            <td><button class="userInput" ng-click="appendToExpression('+')">+</button></td>
          </tr>
          <tr>
            <td><button class="userInput" ng-click="appendToExpression(4)">4</button></td>
            <td><button class="userInput" ng-click="appendToExpression(5)">5</button></td>
            <td><button class="userInput" ng-click="appendToExpression(6)">6</button></td>
            <td><button class="userInput" ng-click="appendToExpression('-')">-</button></td>
          </tr>
          <tr>
            <td><button class="userInput" ng-click="appendToExpression(7)">7</button></td>
            <td><button class="userInput" ng-click="appendToExpression(8)">8</button></td>
            <td><button class="userInput" ng-click="appendToExpression(9)">9</button></td>
            <td><button class="userInput" ng-click="appendToExpression('*')">*</button></td>
          </tr>
          <tr>
            <td><button class="userInput" ng-click="appendToExpression(0)">0</button></td>
            <td><button class="userInput" ng-click="clear()">C</button></td>
            <td><button class="userInput" ng-click="calculate()">=</button></td>
            <td><button class="userInput" ng-click="appendToExpression('/')">/</button></td>
          </tr>
        </table>
    </div>
  </div>
</section>

<script src="<?php echo $base_url;?>asset/js/page/calculator.js"></script>
</body>
</html>
