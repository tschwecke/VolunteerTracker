<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/InterestAreaSvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class InterestAreaController extends BaseController {

	public function getAll() {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "InterestAreas")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new InterestAreaSvc();
		$interestAreas = $svc->getAll();

		usort($interestAreas, function($a, $b)
		{
			return ($b->sortOrder - $a->sortOrder);
		});

		$this->sendResponse(200, $interestAreas);
	}

	public function create() {

		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "InterestAreas")) {
			$this->sendResponse(403);				
			return;
		}
		$interestArea = $this->deserializeRequestBody('InterestArea');

		$svc = new InterestAreaSvc();
		$svc->save($interestArea);

		$this->sendResponse(200, $interestArea);				
	}

	public function update($id) {

		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "InterestAreas")) {
			$this->sendResponse(403);				
			return;
		}
		$interestArea = $this->deserializeRequestBody('InterestArea');

		if($interestArea->id != $id) {
			$this->sendResponse(400, array('message'=>'The id in the path does not match the id in the request body.'));
		}
	
		$svc = new InterestAreaSvc();
		$svc->save($interestArea);

		$this->sendResponse(200, $interestArea);				
	}
}
