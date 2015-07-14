<!DOCTYPE html>
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
	function Paginator() {
		this.page = 0;
		this.pages = 0;
		this.elements = 0;
		this.setElementsCount = function(indexes) {
			this.elements = indexes;
			this.countPages();
		};
		this.allowSetPage = function(_page) {
			return _page>=0 && _page<this.pages;
		};
		this.setPage = function(num) {
			if (this.allowSetPage(num))
				this.page = num;
		};
		this.onpage = 0;
		this.setOnpage = function(num) {
			this.onpage = num;
			this.countPages();
			//this.page = 0;
		};
		
		this.countPages = function () {
			if (this.elements == 0 ) this.pages = 0; else
			this.pages = Math.ceil(this.elements/this.onpage);
			this.page = 0;
			//if ($scope.userdata.length>$scope.pages*$scope.onpage) $scope.pages = $scope.pages + 1;
		};
		
	}
	
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
		$scope.onpages = [5,13,25];
		$scope.paginator = new Paginator();
		$scope.paginator.setOnpage($scope.onpages[0]);
		
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
				$scope.paginator.setElementsCount($scope.userdata.length);
			}).error(function(data, status) {
				$scope.userdata = [];
				$scope.paginator.setElementsCount($scope.userdata.length);
			});
			else 
			{
				$scope.userdata = [];
				$scope.paginator.setElementsCount($scope.userdata.length);
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
				<li class="{{paginator.allowSetPage(paginator.page - 1)?'enabled':'disabled'}}" ng-click="paginator.setPage(paginator.page - 1)"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
				<li ng-repeat="i in [] | range:(paginator.pages)" class="{{paginator.page == $index ? 'active' : ''}}" ng-click = "paginator.setPage($index)"><a href="#" >{{$index + 1 }}<span class="sr-only">(current)</span></a></li>
				<li class="{{paginator.allowSetPage(paginator.page + 1)?'enabled':'disabled'}}" ng-click="paginator.setPage(paginator.page + 1)"><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
			</ul>
		</nav>
		</div>
       <table class="table table-striped">
	   <thead>
		<tr>
		<th><!--button type="button" class="btn btn-warning" ng-show ="allowSetPage(page-1)" ng-click="setPage(page-1)"><</button--></th>
		<th>Фамилия Имя</th>
		<th>Email</th>
		<th>Username</th>
		<th>Дата рождения <!--button type="button" class="btn btn-warning" ng-show ="allowSetPage(page+1)" ng-click="setPage(page+1)">></button--></th>
		</tr>
		</thead>
		<tr ng-repeat="userd in userdata" ng-if="$index >= paginator.page*paginator.onpage && $index < (paginator.page + 1)* paginator.onpage">
		<td><img src="{{userd.user.picture.thumbnail}}" alt = "{{userd.user.username}}"/></td>
		<td>{{userd.user.name.last + ' ' + userd.user.name.first}}</td>
		<td>{{userd.user.email}}</td>
		<td>{{userd.user.username}}</td>
		<td>{{printDate(userd.user.registered)}}</td>
		</tr>
	 </table>
		<span ng-show = "paginator.pages==0">Sorry, nobody</span>
      <hr>

    </div> <!-- /container -->
	<br>
   	<footer class="navbar navbar-default navbar-fixed-bottom">
      <div class="container">
        <a class="navbar-brand" >{{userdata.length}} users</a>
		<div class="btn-group navbar-text" role="group">
		  <button type="button" ng-repeat="num in onpages" ng-click="paginator.setOnpage(num)" class="btn {{paginator.onpage==num?'btn-primary':'btn-default'}}">{{num}}</button>
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
