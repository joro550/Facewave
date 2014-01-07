facewave.controller('CtrlProfilePage', function($scope, $http, $routeParams, userProvider, postProvider) {
	$scope.friendButton = false;
	$scope.user = {};
	$scope.postProvider = postProvider;

	$scope.getProfilePost = function () {
		postProvider.getProfilePost($routeParams.userId);
	}


	$scope.init = function() {
		$scope.getFriendMap();
		$scope.getUser();
		$scope.getProfilePost();
	}

	$scope.getUser = function() {
		$http.post('php/router.php', {'request' : 'getUserInformation', 'page' : 'Profile', 'data' : $routeParams.userId}).success(function(data) {
			$scope.user = data;
		});
	}

	$scope.getFriendMap = function() {
		$http.post('php/router.php', {'request' : 'getFriendMapping', 'page' : 'Profile', 'data' : $routeParams.userId}).success(function(data) {
			if(data == 'false') {
				$scope.friendButton = true;
			} else if(data == 'true') {
				$scope.friendButton = false;
			}
		});
	}

	$scope.requestFriend = function() {
		$http.post('php/router.php', {'request' : 'requestFriend', 'page' : 'Profile', 'data' : $routeParams.userId}).success();
	}
});