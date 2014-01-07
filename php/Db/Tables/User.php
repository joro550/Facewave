<?php

class User extends TableEntity {

	private $mUser;


	public function getUser() {
		if(!$this->checkLoggedIn()) {
			return false;
		}
		
		if(!isset($this->mUser)) {
			$user = $this->getCreatorFactory()->getNewObject('User');
			$id = $_COOKIE['user'];
			$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $id));
			if(empty($user)) {
				return false;
			}
			$this->mUser = array_pop($user);
		}
		return $this->mUser;
	}
	
	public function checkLoggedIn() {
		if(isset($_COOKIE['user'])) {
			return true;
		}
		return false;
	}

	public function logIn($email, $password) {
		//If the user is all ready logged in we don't have to do it again
		if(isset($_COOKIE['user'])) {	
			return false;
		}
		//Select the user out of the db through it's email address
		$user = $this->getCreatorFactory()->getFilteredObject('User', array('email' => $email));
		if(empty($user)) {
			return false;
		}

		//get the object out of the array
		$user = array_pop($user);

		//Check the password locally
		if($user->getPassword() == crypt($password, 'facewave')) {
			setcookie('user', $user->getId(), time() + 3600 * 1000);
			return true;
		}

		return false;
	}

	public function logout() {
		if(isset($_COOKIE['user'])) {
			setcookie('user', '', time() - 3600);
			//for some reason this works so im leaving it here
			if(isset($_COOKIE['user'])) {
				unset($_COOKIE['user']);
			}
			$this->mUser = null;
		}
		return true;
	}

	public function likePost($postId) {
		$likeMap = $this->getCreatorFactory()->getNewObject('Post_Like_Map');
		return $likeMap->likePost($this->getId(), $postId);
	}

	public function unlikePost($postId) {
		$likeMap = $this->getCreatorFactory()->getNewObject('Post_Like_Map');
		return $likeMap->unlikePost($this->getId(), $postId);
	}

	public function acceptFriendRequest($friendId) {
		$request = $this->getCreatorFactory()->getNewObject('Friend_Request');
		return $request->acceptRequest($this->getId(), $friendId);
	}

	public function ignoreFriendRequest($ignoreId) {
		$request = $this->getCreatorFactory()->getNewObject('Friend_Request');
		return $request->ignoreRequest($this->getId(), $ignoreId);
	}

	public function searchUsersByName() {

	}

	public function getFriends() {
		$map = $this->getCreatorFactory()->getNewObject('Friend_Map');
		return $map->getFriendMapping($this->getId());
	}
}