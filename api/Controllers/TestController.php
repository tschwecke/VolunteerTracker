<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/VolunteerSvc.php';
require_once 'Util/AuthorizationMgr.php';

class TestController extends BaseController {

	public function deleteTestVolunteer($id) {

		$svc = new VolunteerSvc();
		$volunteer = $svc->getById($id);

		//If the volunteer email does not start with 'test-' then don't delete it, just return 404
		$isTestEmail = (strpos($volunteer->emailAddress, "test-") === 0);
		if($volunteer == null || !$isTestEmail) {
			$this->sendResponse(404);				
			return;
		}

		$volunteer = $svc->delete($id);
		
		$this->sendResponse(204);
	}

}

