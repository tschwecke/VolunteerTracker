<?php
require_once 'Domain/RightSvc.php';

class AuthorizationMgr {

	public function hasRight() {
		list($requestingUserId, $action, $asset, $assetOwnerId) = func_get_args();

		$requestedRight = $this->constructRightString($requestingUserId, $action, $asset, $assetOwnerId);

		$rightSvc = new RightSvc();
		$rights = $rightSvc->getByVolunteerId($requestingUserId);
		for($i=0; $i<count($rights); $i++) {
			if($rights[$i]->code == $requestedRight) {
				return true;
			}
		}

		return false;
	}

	protected function constructRightString($requestingUserId, $action, $asset, $assetOwnerId) {
		$right = $action . $this->getAssetModifier($requestingUserId, $assetOwnerId) . $asset;

		return $right;
	}

	protected function getAssetModifier($requestingUserId, $assetOwnerId) {
		if (is_null($assetOwnerId)) {
			return "";
		}
		elseif ($requestingUserId == $assetOwnerId) {
			return "My";
		}
		else {
			return "Others";
		}
	}
}


