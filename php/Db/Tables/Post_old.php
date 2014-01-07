<?php



/**
	MAJOR CHANGE INCOMING 

	- funciton to get the posts from the database
	- function that accepts posts as a parameter and then puts them into a formatted array

	Will stop one function being unruley 
	nicer logic paths for GETTING posts
	SOON tm



*/















class Post extends TableEntity {
	public function getPosts($userId, $postId = null, $getFriendPost = null) {
		$user = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $userId));
		$user = array_pop($user);
		$friendMapping = $this->getCreatorFactory()->getNewObject('Friend_Map');
		
		//get the users friends
		if(is_null($postId)) {
			$friends = $friendMapping->getFriendMapping($userId);
			if($friends && is_null($getFriendPost)) {
				$users = array();	
				$users[] = $userId;
				foreach($friends as $friend) {
					$users[] = $friend->getId();
				}
				$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('user_posted' => $users));

			} else {
				//if the user does not have friends then we should show only their posts
				$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('user_posted' => $userId));
			}
		} else {
			if(!is_null($postId)) {
				$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('id' => $postId));
			}
		}
		
		$returnArray = array();
		$likeMap = $this->getCreatorFactory()->getNewObject('Post_Like_Map');
		foreach($posts as $post) {
			$postInfo = array();
			$postInfo['id'] = $post->getId();
			$postInfo['message'] = $post->getMessage();
			$postInfo['like'] = $likeMap->getLikeInfo($post->getId());


			$userPosted = $post->getUser_posted();
			if($userId == $userPosted) {
				$postInfo['username'] = $user->getFirst_name() . " " . $user->getLast_name();
				$postInfo['userId'] = $user->getId();

			} else {
				if(isset($friends[$userPosted])) {
					$postInfo['username'] = $friends[$userPosted]->getFirst_name() . " " . $friends[$userPosted]->getLast_name();
					$postInfo['userId'] = $friends[$userPosted]->getId();
				}
			}
		}
			
		$returnArray[$post->getId()] = $postInfo;
		
		return $returnArray;
	}

	public function getPost($postId) {


	}

	public function getWallPosts() {

	}

	public function getProfilePosts() {

	}

	private function getFormattedPosts($posts) {
		
	}
	
}