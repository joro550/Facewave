<?php

class Friend_Request extends TableEntity {
	public function getRequests($userId) {
		$requests = $this->getCreatorFactory()->getFilteredObject('Friend_Request', array('to_user' => $userId, 'deleted' => '0'));
		$returnArray = array();
		foreach($requests as $request) {
			$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $request->getFrom_user()));
			if(!empty($user)) {
				$user = array_pop($user);
				$returnArray[] = $user->getTableEntity();
			}
		}
		return $returnArray;
	}


	public function acceptRequest($toId, $fromId) {
		$request = $this->getCreatorFactory()->getFilteredObject('Friend_Request', array('to_user' => $toId, 'from_user' => $fromId, 'deleted' => '0'));
		if(!empty($request)) {
			$request = array_pop($request);
			//Check to see if they have an exsisting mapping - no point in them being friends twice
			$friendMap = $this->getCreatorFactory()->getNewObject('Friend_Map');
			$friends = $friendMap->getFriendMapping($toId);
			if(!empty($friends) && isset($friends[$fromId])) {
				$request->delete();
				return false;
			}
			//Map them as friends
			$map = $this->getCreatorFactory()->getNewObject('Friend_Map');
			$map->setUser($toId);
			$map->setFriend($fromId);
			if($map->commit() == FALSE) {
				return false;
			}
			$request->delete();
			return true;
		}
		return false;
	}

	public function ignoreRequest($toId, $fromId) {
		$request = $this->getCreatorFactory()->getFilteredObject('Friend_Request', array('to_user' => $toId, 'from_user' => $fromId, 'deleted' => '0'));
		if(is_array($request) && !empty($request)) {
			$request = array_pop($request);
			if($request->delete()) {
				return true;
			}
		}
		return false;
	}

	public function requestFriend($fromUser, $toUser) {

		$request = $this->getCreatorFactory()->getFilteredObject('Friend_Request', array('to_user' => $toUser, 
																					'from_user' => $fromUser,
																					'deleted' => '0'));
		if(!empty($request)) {
			return false;
		}
		unset($request);
		$request = $this->getCreatorFactory()->getFilteredObject('Friend_Request', array('from_user' => $toUser,
																					'to_user'  => $fromUser,
																					'deleted' => '0'));
		if(!empty($request)) {
			return false;
		}
		unset($request);

		$friendRequest = $this->getCreatorFactory()->getNewObject('Friend_Request');
		$friendRequest->setTo_user($toUser);
		$friendRequest->setFrom_user($fromUser);
		if($friendRequest->commit() == FALSE) {
			return false;
		}
		return true;
	}
}