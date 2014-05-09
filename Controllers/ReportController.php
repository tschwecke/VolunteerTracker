<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/ReportSvc.php';
require_once 'Util/AuthorizationMgr.php';

class ReportController extends BaseController {

	public function getFamilyHours() {

		//$authenticatedUserId = $this->getAuthenticatedUserId();
		//$authorizationMgr = new AuthorizationMgr();
		//if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllVolunteerInfo")) {
		//	$this->sendResponse(403);				
		//	return;
		//}

		$svc = new ReportSvc();
		$families = $svc->getFamilyHours();



		$this->sendResponse(200, $families);
	}

}

