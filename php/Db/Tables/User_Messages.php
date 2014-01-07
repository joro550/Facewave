<?php
class User_Messages extends TableEntity {
	public function getMessages($userId) {
		$messages = $this->getCreatorFactory()->getFilteredObject('User_Messages', array('to_user' => $userId));
		if(empty($messages)) {
			return false;
		}

		$users = array();
		foreach($messages as $msg) {
			$users[] = $msg->getFrom_user();
		}
		
		$returnArray = array();
		if(!empty($users)) {
			$users = $this->getCreatorFactory()->getFilteredObject('User', array('id' => $users));
			foreach($messages as $msg) {
				if(!isset($returnArray[$msg->getFrom_user()])) {
					$returnArray[$msg->getFrom_user()] = array('user' => 
													$users[$msg->getFrom_user()]->getTableEntity(array('id', 'first_name', 'last_name')));
				}
				$returnArray[$msg->getFrom_user()]['message'][$msg->getId()] = $msg->getTableEntity();
			}
		}
		return $returnArray;
	}
}