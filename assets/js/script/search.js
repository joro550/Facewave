facewave.controller('CtrlSearch', function($scope, $http, $routeParams, userProvider, postProvider) {
	$scope.results;
	$scope.noResultsError = true;
	$scope.postProvider = postProvider;

	$scope.search = function() {
		var searchString = $routeParams.searchString;
		$http.post('php/router.php', {'request' : 'search', 'page': 'Search', 'searchString': searchString}).success(function(data) {
			if(data) {
				$scope.user = data.results.user;
				$scope.noResultsError = false;
				
				$scope.postProvider.message = data.results.post;
			} else {
				$scope.noResultsError = true;
			}
		});
	}
});