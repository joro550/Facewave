<?php

class Post_Like_map extends TableEntity {
	public function getLikeCount($postId) {
		if(!isset($postId)) {
			return false;
		}

		$post = $this->getCreatorFactory()->getFilteredObject('Post', array('id' => $postId, 'deleted' => '0'));
		if(!empty($post)) {
			$likes = $this->getCount('id', array('post_id' => $postId, 'deleted' => '0'));
			return $likes;
		}
		return false;
	}


	public function getUserLike($postId) {
		if(!isset($postId)) {
			return false;
		}

		$post = $this->getCreatorFactory()->getFilteredObject('Post', array('id' => $postId, 'deleted' => '0'));
		$userInfo = array();
		
		if(!empty($post)) {
			$likes = $this->getCreatorFactory()->getFilteredObject('Post_Like_Map', array('post_id' => $postId, 'deleted' => '0'));
			if(!empty($likes)) {
				foreach($likes as $like) {
					$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $like->getUser_id()));
					if(!empty($user)) {
						$user = array_pop($user);
						if(!isset($userInfo[$user->getId()])) {
							$userInfo[$user->getId()] = $user->getTableEntity(array('id', 'first_name', 'last_name'));
						}
					}
				}
			}
			return $userInfo;
		}
		return $userInfo;
	}


	public function getLikeInfo($postId) {
		$returnArray = array();
		$returnArray['LikeCount'] = $this->getLikeCount($postId); 
		$returnArray['UserInfo'] = $this->getUserLike($postId);

		if(empty($returnArray['UserInfo'])) {
			$returnArray['UserInfo'] = new stdClass;
			$returnArray['UserLiked'] = false;
		} else {
			$user = $this->getCreatorFactory()->getNewObject('User');
			$user = $user->getUser();
			if(isset($returnArray['UserInfo'][$user->getId()])) {
				$returnArray['UserLiked'] = true;
			} else {
				$returnArray['UserLiked'] = false;
			}
		}

		return $returnArray;
	}

	public function likePost($userId, $postId) {
		if(is_null($userId) || is_null($postId)) {
			return false;
		}
		$likeMap = $this->getCreatorFactory()->getFilteredObject('Post_Like_Map', array('user_id' => $userId, 'post_id' => $postId));
		if(!empty($likeMap)) {
			//We all ready have a like map
			$likeMap = array_pop($likeMap);
			$deleted = $likeMap->getDeleted();
			if($deleted) {
				//it has been "deleted" but it's still there, no points in duplicates!!
				$likeMap->setDeleted(0);
				return $likeMap->commit();
			}
		} else {
			unset($likeMap);

			$likeMap = $this->getCreatorFactory()->getNewObject('Post_Like_Map');
			$likeMap->setUser_id($userId);
			$likeMap->setPost_id($postId);
			$likeMap->setLike_status(1);
			return $likeMap->commit();
		}
		return false;
	}

	public function unlikePost($userId, $postId) {
		$likeMap = $this->getCreatorFactory()->getFilteredObject('Post_Like_Map', array('user_id' => $userId, 'post_id' => $postId, 
																						'deleted' => '0'));
		if(!empty($likeMap)) {
			$likeMap = array_pop($likeMap);
			return $likeMap->delete();
		} else {
			return false;
		}
	}
}