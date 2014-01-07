<?php

class ProfilePage  extends Page {
	public function getFriendMapping() {
		$user = $this->getFactory()->getNewObject('User');
		$user = $user->getUser();

		if(!isset($this->mData->data) ||
			$this->mData->data == $user->getId() ||
			$this->mData->data ==  0) {
			return true;
		}

		$friendMap = $user->getFriends();
		if(isset($friendMap[$this->mData->data])) {
			return true;
		} 
		return false;
	}

	public function requestFriend() {
		$user = $this->getFactory()->getNewObject('User');
		$user = $user->getUser();

		if(isset($this->mData->data) && 
			$this->mData->data != 0 && $this->mData->data != $user->getId()) {
			//Call friend request function 
			$friendRequest = $this->getFactory()->getNewObject('Friend_Request');
			return $friendRequest->requestFriend($user->getId(), $this->mData->data);
		}
		return false;
	}

	public function getUserInformation() {
		$user = $this->getFactory()->getNewObject('User');
		$user = $user->getUser();
		if(!isset($this->mData->data) || $this->mData->data == $user->getId()) {
			return $user->getTableEntity();
		} else {
			$user = $this->getFactory()->getFilteredObject('User', array('id' => $this->mData->data));
			if(!empty($user)) {
				$user = array_pop($user);
				return $user->getTableEntity();
			}
		}
	}

	public function getPosts() {
		if(!isset($this->mData->user) || $this->mData->user == 0) {
			$user = $this->getFactory()->getNewObject('User');
			$user = $user->getUser();
		} else {

			$user = $this->getFactory()->getFilteredObject('User', array('id'  => $this->mData->user, 'deleted' => '0'));
			if(!empty($user)) {
				$user = array_pop($user);
			} else {
				return false;
			}
		}
		$posts = $this->getFactory()->getNewObject('Post');
		$posts = $posts->getProfilePosts($user->getId());
		return $posts;
	}
}