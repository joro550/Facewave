<?php
class Website {
	public static function error($message) {
		return array('error' => $message);
	}

	public static function handleResponce($responce) {
		// if(!is_array($responce)) {
			echo json_encode($responce);
		// } else {
			// echo json_encode($responce);
		// }
	}

	public function handleRequest($request) {
		if(isset($request->page)) {
			$page =  $request->page . 'Page';
			//More page specific requests
			if(file_exists(getcwd() . '/Classes/Page/' . $page . '.php')) {
				include_once(getcwd() . '/Classes/Page/' . $page . '.php');

				$page = new $page();
				$page->handleRequest($request);
			} else {
				Website::handleResponce(Website::error('Page did not exist ' . $page));
			}
		} else {
			if(method_exists($this, $request->request)) {
				$this->mData = $request;
				Website::handleResponce($this->{$request->request}());
			} else {
				Website::handleResponce(Website::error('Method did not exist'));
			}
		}
	}
}

