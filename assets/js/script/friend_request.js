facewave.controller('CtrlFriendRequest', function($scope, $http, $routeParams, $location, userProvider) {
	$scope.requests = {};
	$scope.noRequestMessage = false;

	$scope.init = function() {
		$scope.getRequests();
	}

	$scope.acceptRequest = function(friendId) {
		$http.post('php/router.php', {'request' : 'acceptRequest', 'page' : 'FriendRequest', 'data' : friendId}).success(function(data) {
			$scope.getRequests();
		});
	}

	$scope.getRequests = function() {
		$http.post('php/router.php', {'request' : 'getRequests', 'page' : 'FriendRequest'}).success(function(data) {
			$scope.requests = {};
			if(data.requests) {
				$scope.requests = data;
				$scope.noRequestMessage = false;
			} else {
				$scope.noRequestMessage = true;
			}
		});
	}

	$scope.ignoreRequest = function(ignoreId) {
		$http.post('php/router.php', {'request' : 'ignoreRequest', 'page' : 'FriendRequest', 'data' : ignoreId}).success(function() {
			$scope.getRequests();
		});
	}
});