<?php

class Friend_Map extends TableEntity {
	public function getFriendMapping($userId) {
		$dbFriends = $this->getCreatorFactory()->getFilteredObject('friend_map', array('user' => $userId));

		$friendArray = array();

		foreach($dbFriends as $friend) {
			$friendArray[] = $friend->getFriend();
		}
		unset($dbFriends);
		unset($friend);
		
		$dbFriends  = $this->getCreatorFactory()->getFilteredObject('friend_map', array('friend' => $userId));
		foreach ($dbFriends as $friend) {
			$friendArray[] = $friend->getUser();
		}
		unset($friend);
		unset($dbFriends);
		
		if(!empty($friendArray)) {
			$users = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $friendArray));
			return $users;
		}
		return false;
	}
}