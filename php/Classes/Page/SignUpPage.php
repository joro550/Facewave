<?php
class SignUpPage extends Page {

	public function createUser() {
		if(empty($this->mData->data)) {
			return false;
		} else {
			$data = $this->mData->data;
			//Check some values
			if(empty($data->firstname) ||
				empty($data->lastname) ||
				empty($data->email)	   ||
				empty($data->password)) {
				return false;
			} 
			$user = $this->getFactory()->getNewObject('User');
			$user->setFirst_name($data->firstname);
			$user->setLast_name($data->lastname);
			$user->setEmail($data->email);
			$user->setPassword(crypt($data->password, 'facewave'));

			if($user->commit() === FALSE) {
				return FALSE;
			}
			return TRUE;
		}
	}
}
