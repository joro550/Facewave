<?php

class Post extends TableEntity {
	public function getPost($postId) {	
		$post = $this->getCreatorFactory()->getFilteredObject('Post', array('id' => $postId, 'deleted' => '0'), 'time');
		if(!empty($post)) {
			$tempPost = $post;
			$tempPost = array_pop($tempPost);
			$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' =>  $tempPost->getUser_posted(), 'deleted' => '0'));
			if(!empty($user)) {
				return $this->getFormattedPosts($post, $user);
			}
		}
		return false;
	}

	public function getWallPosts() {
		$user = $this->getCreatorFactory()->getNewObject('User');
		$user = $user->getUser();
		if($user != false)  {
			$friendMapping = $this->getCreatorFactory()->getNewObject('Friend_Map');
			$friends = $friendMapping->getFriendMapping($user->getId());
			// echo "herWTFe\n";
			$users = array();
			$users[$user->getId()] = $user;
			if(!empty($friends)) {
				foreach($friends as $friend) {
					$users[$friend->getId()] = $friend;
				}
			}
			$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('user_posted' => array_keys($users), 'deleted' => '0'), 'time');
			return $this->getFormattedPosts($posts, $users);

		}
		return false;
	}

	public function getProfilePosts($userId) {
		$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('user_posted' => $userId, 'deleted' => '0'));
		$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $userId, 'deleted' => '0'));
		
		return $this->getFormattedPosts($posts, $user);
	}

	public function search($searchString) {
		$searchResults = parent::search($searchString);
		if(empty($searchResults)) {
			return false;
		}
		//Get post stuff
		$userArray = array();

		$userId = array();
		foreach ($searchResults as $post) {
			$userId[] = $post->getUser_posted();
		}

		$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $userId, 'deleted' => '0'));
		if(empty($user)) {
			return false;
		}
		return $this->getFormattedPosts($searchResults, $user);
	}

	private function getFormattedPosts($posts, $users) {
		$returnArray = array();
		// print_r($posts);
		if(is_array($posts)) {
			$likeMap = $this->getCreatorFactory()->getNewObject('Post_Like_Map');
			foreach($posts as $post) {
				$postInfo = array();
				$postInfo['id'] = $post->getId();
				$postInfo['message'] = $post->getMessage();
				$postInfo['like'] = $likeMap->getLikeInfo($post->getId());

				$userPosted = $post->getUser_posted();

				if(isset($users[$userPosted])) {
					$postInfo['username'] = $users[$userPosted]->getFirst_name() . ' ' . $users[$userPosted]->getLast_name();
					$postInfo['userId'] = $userPosted;
				}

				$contentId = $post->getContent();
				if($contentId != 0) {
					$content = $this->getCreatorFactory()->getFilteredObject('Content', array('id' => $contentId, 'deleted' => 0));
					if(!empty($content)) {
						$content = array_pop($content);
						$postInfo['content'] = $content->getTableEntity(array('type', 'location'));
					}
				}

				$returnArray[$post->getId()] = $postInfo;
			}
		}
		return $returnArray;
	}

	public function searchByLikes($searchString) {
		$user = $this->getCreatorFactory()->getNewObject('User');
		$postLikeMap = $this->getCreatorFactory()->getNewObject('Post_Like_Map');

		$user = $user->getUser();
		// if($user == false){
		// 	return false;
		// }
		$searchResults = parent::search($searchString);
		if(!empty($searchResults)) {
			$users = array();
			$postIds = array();
			foreach($searchResults as $result) {
				$postIds[] = $result->getId();
				$users[] = $result->getUser_posted();
			}
			unset($result);

			$results = $this->runQuery($this->getQueryCreator()->select(array('post_id' => '', $this->getQueryCreator()->count($postLikeMap->getIdField()) => ''))
							->from(get_class($postLikeMap))->where(array('deleted' => '0', 'post_id' => $postIds), 'AND')->group('post_id')->getQueryString());
		
			$users = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $users));
			
			$formattedPosts = $this->getFormattedPosts($searchResults, $users);
			$returnArray = array();
			if(!empty($results)) {
				foreach($results as $result) {
					if(isset($formattedPosts[$result['post_id']])) {
						$formattedPosts[$result['post_id']]['friendLike'] = $result['count'];
					}
				}
			}
			return $formattedPosts;
		} else {
			return false;
		}
	}
	
}