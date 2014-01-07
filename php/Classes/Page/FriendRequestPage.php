<?php

class FriendRequestPage extends Page {
	public function getRequests() {
		$user = $this->getFactory()->getNewObject('User');
		$user = $user->getUser();
		if($user != false) {
			$requests = $this->getFactory()->getNewObject('Friend_Request');
			
			$requests = $requests->getRequests($user->getId());
			if(!empty($requests)) {
				return array('requests' => $requests);
			}

			return array();
		} else {
			return false;
		}
	}

	public function acceptRequest() {
		$user = $this->getFactory()->getNewObject('User');
		$user = $user->getUser();

		return $user->acceptFriendRequest($this->mData->data);
	}

	public function ignoreRequest() {
		$user = $this->getFactory()->getNewObject('User');
		$user = $user->getUser();

		return $user->ignoreFriendRequest($this->mData->data);

	}
} 	