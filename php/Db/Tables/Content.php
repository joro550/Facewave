<?php


class Content extends TableEntity {
	public function upload($file) {
		$extentions = array('image/gif' => '.gif', 'image/jpeg' => '.jpeg', 'image/jpg' => '.jpg', 'image/png' => '.png');
		if(in_array($file['file']['type'], array_keys($extentions))) {
			//Test to see if image is more than 2 MB
			if($file['file']['size'] > 2097152) {
				echo $file['file']['size'];
				return false;
			}


			$content = $this->getCreatorFactory()->getNewObject('Content');
			$content->setType('img');
			if($content->commit() == FALSE) {
				return false;
			}
			//set location
			$location = $content->getId();
			$location = base64_encode($location);
			$content->setLocation($location . '.jpg');

			move_uploaded_file($file['file']['tmp_name'], getcwd() . '/../assets/img/upload/' . $location . '.jpg');
			$content->commit();
			
			return $content;
		}
		return false;
	}


}