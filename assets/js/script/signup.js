facewave.controller('CtrlSignUp', function($scope, $http, $routeParams, $location) {
	$scope.user;
	$scope.message = '';

	$scope.createUser = function() {
		$http.post('php/router.php', {'request': 'createUser', 'page': 'SignUp', 'data': $scope.user}).success(function(data) {
			console.log(data);
			if(data = 'true') {
				$scope.message = 'Sign up successful, you can log in';
				$scope.user = {};
			} else {
				$scope.message = 'Something went wrong please try again';
			}
		});
	}

});