var facewave = angular.module('facewave', ['ngRoute']).
	config(function($routeProvider) {
		$routeProvider.
			when('/', {templateUrl : 'view/login.html'}).
			when('/wall', {templateUrl : 'view/home.html'}).
			when('/signup', {templateUrl: 'view/signup.html'}).
      when('/messages', {templateUrl: 'view/messages.html'}).
      when('/profile', {templateUrl: 'view/profile.html'}).
      when('/friendrequest', {templateUrl: 'view/friend_request.html'}).
      when('/profile/:userId', {templateUrl: 'view/profile.html'}).
      when('/search/:searchString', {templateUrl: 'view/search.html'}).
			otherwise({redirectTo : '/'});
});

facewave.filter('toArray', function() { return function (obj) {
      if (!(obj instanceof Object)) {
          return obj;
      }

      return Object.keys(obj).map(function (key) {
          return Object.defineProperty(obj[key], '$key', {__proto__: null, value: key});
      });
  }
});

facewave.factory('userProvider', function($http, $location) {
  return {
  	// user : null,
  	checkLogin : function(callback) {
  		$http.post('php/router.php', {request: 'checkLogin', page: 'Factory'}).success(function(data) {
        if(data == 'true') {
  				callback();
  			} 
       		return data;
  		});
  	},
    getUser : function(callback) {
      $http.post('php/router.php', {request: 'getUser', page: 'Factory'}).success(function(data) {
        if(!data.error) { 
          callback(data);
        }
      });
    },
    logout : function(callback) {
      $http.post('php/router.php', {request: 'logout', page: 'Factory'}).success(function(data) {
        $location.path('#/');
      });
    }
  };
});

facewave.directive('post', function() {
  return {
    templateUrl: 'view/post.html'
  }
});

facewave.factory('postProvider', function($http) {
  return {
    message : null,
    switch: false,
    likePost : function(postId) {
      var that = this;
      $http.post('php/router.php', {'request' : 'likePost', 'page'  : 'Factory', 'post' : postId}).success(function(data) {
        if(data[postId] && that.message[postId]) {
          that.message[postId] = data[postId];
          that.getPost(postId);
        } else {
          for(var i = 0; i < that.message.length; i ++) {
            if(that.message[i].id  == postId) {
              that.message[i] = data;
            }
          }
        }
      });
    },
    unlikePost : function(postId) {
      var that = this;
      $http.post('php/router.php', {'request' : 'unlikePost', 'page' : 'Factory', 'post' : postId}).success(function(data) {
        if(data[postId] && that.message[postId]) {
          that.message[postId] = data[postId];
          that.getPost(postId);
        }
      });
    },
    getUserLiked : function(postId) {
      if(this.message[postId]) {
        if(this.message[postId].like.UserLiked == true) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },
    getPost : function(postId) {
      var that = this;
      if(that.message[postId]) {
        $http.post('php/router.php', {'request' : 'getPost', 'page' : 'Factory', 'post' : postId}).success(function(data) {
          if(data[postId]) {
            that.message[postId] = data[postId];
          }
        });
      }
    },
    getWallPosts : function () {
      var that = this;
      $http.post('php/router.php', {'request': 'getWallPosts', 'page': 'Main'}).success(function(data) {
        if(data){
          that.message = data;
        }
      });
    },
    getProfilePost : function(userId, callback) {
      var that = this;
      $http.post('php/router.php', {'request' : 'getPosts', 'page' : 'Profile', 'user' : userId}).success(function(data) {
        that.message = data;
        if(callback) {
          callback();
        }
      });
    }
  };
});