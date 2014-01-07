facewave.controller('CtrlLoginPage', function($scope, $http, $routeParams, $location, userProvider) {
	$scope.user = {};
	$scope.errorMessage = '';

	$scope.init = function() {
		if(!userProvider.checkLogin(function() {
			$location.path('/wall');
		}));
	}


	$scope.login = function() {
		$http.post('php/router.php', {'request': 'login', 'page': 'login', 'data': $scope.user}).success(function(data) {
			if(data == 'true') {
				$scope.init();
			} else {
				$scope.errorMessage = 'The email or password were wrong';
			}
		});
	}
});