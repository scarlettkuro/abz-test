﻿<!DOCTYPE html>
<html lang="en" ng-app="myApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <title>ABZ Test Task</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script>
	
	//var yete;
	var myApp = angular.module('myApp',[]);
	
	myApp.filter('range', function() {
	  return function(val, range) {
		range = parseInt(range);
		for (var i=0; i<range; i++)
		  val.push(i);
		return val;
	  };
	});
	
	myApp.controller('userController', function($scope, $http) {
		$scope.userdata = [];
		$scope.page = 0;
		$scope.pages = 0;
		
		$scope.allowChangePage = function(page) {
			return page>=0 && page<$scope.pages;
		};
		$scope.setPage = function(num) {
		if ($scope.allowChangePage(num))
			$scope.page = num;
		};
		$scope.countPages = function () {
			if ($scope.userdata.length == 0 ) $scope.pages = 0; else
			$scope.pages = Math.ceil($scope.userdata.length/$scope.onpage);
			//if ($scope.userdata.length>$scope.pages*$scope.onpage) $scope.pages = $scope.pages + 1;
		};
		$scope.onpages = [5,13,25];
		$scope.onpage = $scope.onpages[0];
		$scope.setOnpage = function(num) {
			$scope.onpage = num;
			$scope.countPages();
			$scope.page = 0;
		};
		
		$scope.printDate= function (timestamp) {
			var temp = new Date( Number(timestamp*1000)); 
			return temp.toDateString();
		}
		$scope.load = function(n) {
		if (!isNaN(n))
			if (n>=0)
			$http({
				responseType : 'json',
				url: 'http://api.randomuser.me/?results=' + n
			}).success(function(data, status) {
				$scope.userdata = data.results;
				$scope.countPages();
			}).error(function(data, status) {
				$scope.userdata = [];
				$scope.countPages();
			});
			else 
			{
				$scope.userdata = [];
				$scope.countPages();
			}	
		};
		$scope.load(63);
		//$scope.setOnpage($scope.onpages[0]); //set num of pages
	});
	</script>
  </head>

  <body ng-controller="userController">

   

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello, ABZ!</h1>
        <p>This is a test task you gave me. Pealse, enjoy.</p>
      </div>
    </div>
    <div class="container" >
	<div class="text-center">
		<nav>
			<ul class="pagination">
				<li class="{{allowChangePage(page - 1)?'enabled':'disabled'}}" ng-click="setPage(page - 1)"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
				<li ng-repeat="i in [] | range:pages" class="{{page == $index ? 'active' : ''}}" ng-click = "setPage($index)"><a href="#" >{{$index + 1 }}<span class="sr-only">(current)</span></a></li>
				<li class="{{allowChangePage(page + 1)?'enabled':'disabled'}}" ng-click="setPage(page + 1)"><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
			</ul>
		</nav>
		</div>
       <table class="table table-striped">
	   <thead>
		<tr>
		<th><!--button type="button" class="btn btn-warning" ng-show ="allowChangePage(page-1)" ng-click="setPage(page-1)"><</button--></th>
		<th>Фамилия Имя</th>
		<th>Email</th>
		<th>Username</th>
		<th>Дата рождения <!--button type="button" class="btn btn-warning" ng-show ="allowChangePage(page+1)" ng-click="setPage(page+1)">></button--></th>
		</tr>
		</thead>
		<tr ng-repeat="userd in userdata" ng-if="$index >= page*onpage && $index < (page + 1)* onpage">
		<td><img src="{{userd.user.picture.thumbnail}}" alt = "{{userd.user.username}}"/></td>
		<td>{{userd.user.name.last + ' ' + userd.user.name.first}}</td>
		<td>{{userd.user.email}}</td>
		<td>{{userd.user.username}}</td>
		<td>{{printDate(userd.user.registered)}}</td>
		</tr>
	 </table>
		<span ng-show = "pages==0">Sorry, nobody</span>
      <hr>

    </div> <!-- /container -->
	<br>
   	<footer class="navbar navbar-default navbar-fixed-bottom">
      <div class="container">
        <a class="navbar-brand" >{{userdata.length}} users</a>
		<div class="btn-group navbar-text" role="group">
		  <button type="button" ng-repeat="num in onpages" ng-click="setOnpage(num)" class="btn {{onpage==num?'btn-primary':'btn-default'}}">{{num}}</button>
		</div>
		<p class="navbar-text ">Do you wanna another number of users?</p>
		<form class="navbar-form navbar-left" role="search">
			<div class="form-group">
				<input type="text" class="form-control" ng-model="usercount" placeholder="Enter number here">
			</div>
			<button type="submit" class="btn btn-default" ng-click="load(usercount)">Try</button>
		</form>
		
      </div>
    </footer>
    <!-- Placed at the end of the document so the pages load faster -->
    
  </body>
</html>
