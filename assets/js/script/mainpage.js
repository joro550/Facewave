facewave.controller('CtrlMainPage', function($scope, $http, $routeParams, $location, userProvider, postProvider) {
	$scope.message = {};
	$scope.newMessage;
	$scope.user = {};
	$scope.likeButton = false;
	$scope.postProvider = postProvider;

	$scope.init = function() {
		userProvider.getUser(function(data) {
			if(data == 'false') {
				$location.path('#/');
			}
			$scope.user = data;
		});
	}

	$scope.postMessage = function() {
		$http.post('php/router.php', {'request': 'postMessage', 'page': 'Main', 'data': $scope.newMessage}).success(function(data) {
			postProvider.getWallPosts();
			$scope.newMessage = '';
		});
	}

	$scope.logout = function() {
		userProvider.logout();
	}

	$scope.unlikePost = function(postId) {
		$http.post('php/router.php', {'request' : 'unlikePost', 'page' : 'Factory', 'post' : postId}).success(function(data) {
			if(data[postId]) {
				$scope.message[postId] = data[postId];
			}
		});
	}
	
	$scope.uploadFile = function(files) {
	    var fd = new FormData();
	    //Take the first selected file
	    if(!files[0]) {
	    	return false;
	    }
	    fd.append("file", files[0]);
	    fd.append('request', 'upload');
	    fd.append('page', 'Factory');
	    fd.append('message', $scope.newMessage);

	    $http.post('php/router.php', fd, {
	        withCredentials: true,
	        headers: {'Content-Type': undefined },
	        transformRequest: angular.identity
	    }).success(function(data) {
	    	files = null;
	    	$scope.getPosts();

	    });
	}
});