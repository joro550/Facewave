<?php
class MainPage extends Page {
	public function postMessage() {
		if(!isset($this->mData->data)) {
			return false;
		}
		$user = $this->getFactory()->getNewObject('User');
		$userId = $user->getUser()->getId();

		$post = $this->getFactory()->getNewObject('Post');
		$post->setMessage($this->mData->data);
		$post->setUser_posted($userId);
		$post->setTime(date('Y-m-d H:i:s'));
		if($post->commit() == FALSE) {
			return false;
		}
		return true;
	}

	public function getWallPosts() {
		$post = $this->getFactory()->getNewObject('Post');
		return $post->getWallPosts();
	}
}
