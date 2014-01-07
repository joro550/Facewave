<?php
/**
	This class is for anything that is common between pages i.e. getting posts
	Or liking etc.

**/
class FactoryPage extends Page {
	public function checkLogin() {
		$user = $this->getFactory()->getNewObject('User');
		return $user->checkLoggedIn();
	}

	public function getUser() {
		$user = $this->getFactory()->getNewObject('User');
		$info = $user->getUser();
		if($info != false) {
			return $info->getTableEntity();
		}
		return false;
	} 

	public function logout() {
		$user = $this->getFactory()->getNewObject('User');
		return $user->logout();
	}

	public function likePost() {
		if(!isset($this->mData->post)) {
			return false;
		}

		$user = $this->getFactory()->getNewObject('User');
		$post = $this->getFactory()->getNewObject('Post');
		$user = $user->getUser();
		if($user != false) {
			$user->likePost($this->mData->post);

			$post = $post->getPost($this->mData->post);
			return $post;
		} else {
			return false;
		}
	}

	public function unlikePost() {
		if(!isset($this->mData->post)) {
			return false;
		}

		$user = $this->getFactory()->getNewObject('User');
		$post = $this->getFactory()->getNewObject('Post');
		$user = $user->getUser();
		if($user != false) {
		 	$user->unlikePost($this->mData->post);

			$post = $post->getPost($this->mData->post);
			return $post;
		} else {
			return false;
		}
	}

	public function getPost() {
		if(!isset($this->mData->post)) {
			return false;
		}
		$post = $this->getFactory()->getNewObject('Post');
		$post = $post->getPost($this->mData->post);
		return $post;
	}

	public function upload() {
		$content = $this->getFactory()->getNewObject('Content');
	 	$content = $content->upload($_FILES);
	 	if($content != false) {
	 		//Create a post
	 		$user = $this->getFactory()->getNewObject('User');
	 		$post = $this->getFactory()->getNewObject('Post');

	 		$user = $user->getUser();
	 		if($user != false) {
	 			$post->setUser_posted($user->getId());
	 			$post->setContent($content->getid());
	 			if(isset($this->mData->message)) {
	 				$post->setMessage($this->mData->message);
	 			}
	 			$post->commit();
	 			return true;
	 		}
	 	}
	}
}