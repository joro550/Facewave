facewave.controller('CtrlMessages', function($scope, $http, $routeParams, $location, userProvider) {
	$scope.userMessages = null;

	$scope.init = function() {
		$scope.getMessages();
	}

	$scope.getMessages = function() {
		$http.post('php/router.php', {'request': 'getMessages', 'page': 'Message'}).success(function(data) {
			$scope.userMessages = data;
		});
	}
});