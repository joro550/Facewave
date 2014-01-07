<?php

class SearchPage extends Page {
	public function search() {
		if(!isset($this->mData->searchString)) {
			return false;
		}
		//Get 
		$users = $this->getFactory()->getNewObject('User');
		$users = $users->search($this->mData->searchString);
		$userArray = array();
		if(!empty($users)) {
			foreach($users as $user) {
				$userArray[] = $user->getTableEntity(array('id', 'first_name', 'last_name', 'email'));
			}
		} else {
			$userArray = new stdClass;
		}

		$posts = $this->getFactory()->getNewObject('Post');
		$postArray = $posts->searchByLikes($this->mData->searchString);
		//
		return array('results' => array('user' => $userArray, 'post' => $postArray));
	}

	public function sortPost($posts) {
		



	}
}