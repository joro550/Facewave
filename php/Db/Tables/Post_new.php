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

	public function getPost($postId) {
		$user = $this->getCreatorFactory()->getNewObject('User');
		$user = $user->getUser();
		if($user != false)  {
			$friendMapping = $this->getCreatorFactory()->getNewObject('Friend_Map');
			$friends = $friendMapping->getFriendMapping($user->getId());
			$users = array();
			$user[$user->getId()] = $user
			if(!empty($friends)) {
				foreach($friends as $friend) {
					$users[$friend->getId()] = $friend
				}
			}
			$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('user_posed' => $postId));
			return $this->getFormattedPosts($posts, $users);
		}
		return false;
	}

	public function getWallPosts() {
		$user = $this->getCreatorFactory()->getNewObject('User');
		$user = $user->getUser();
		if($user != false)  {
			$friendMapping = $this->getCreatorFactory()->getNewObject('Friend_Map');
			$friends = $friendMapping->getFriendMapping($user->getId());
			$users = array();
			$user[$user->getId()] = $user
			if(!empty($friends)) {
				foreach($friends as $friend) {
					$users[$friend->getId()] = $friend
				}
			}
			$posts = $this->getCreatorFactory()->getFilteredObject('Post', array('user_posed' => array_keys($users)));
			return $this->getFormattedPosts($posts, $users);

		}
		return false;
	}

	public function getProfilePosts() {
		//get user specific posts
		///format 
		//return
	}

	private function getFormattedPosts($posts, $users) {
		if(is_array($posts)) {
			$returnArray = array();
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
			}
			$returnArray[$post->getId()] = $postInfo;
		}
		return $returnArray;
	}
	
}