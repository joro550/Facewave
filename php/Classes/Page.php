<?php
class Page {
	private $mFact;
	protected $mData;

	protected function getFactory() {
		if(!isset($this->mFact)) {
			$this->mFact = new Factory();
		}
		return $this->mFact;
	}

	public function handleRequest($request) {
		if(method_exists($this, $request->request)) {
			$this->mData = $request;
			Website::handleResponce($this->{$request->request}());
		} else {
			Website::handleResponce(Website::error('Method did not exist'));
		}
	}
}
