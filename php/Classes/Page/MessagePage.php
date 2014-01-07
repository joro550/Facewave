<?php
class MessagePage extends Page {
	public function getMessages() {
		$user = $this->getFactory()->getNewObject('User');
		$messages = $this->getFactory()->getNewObject('User_Messages');
		
		$user = $user->getUser();
		if($user == false) {
			return false;
		}
		return $messages->getMessages($user->getId());
	}
}