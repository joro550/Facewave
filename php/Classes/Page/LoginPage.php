<?php

class LoginPage extends Page {
	public function login() {
		$requestData = $this->mData->data;
		$user = $this->getFactory()->getNewObject('User');
		if(!isset($requestData->email) || !isset($requestData->password)) {
			return false;
		}

		return $user->login($requestData->email, $requestData->password);
	}
}