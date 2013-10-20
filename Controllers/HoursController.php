<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/HoursSvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class HoursController extends BaseController {

	public function getByVolunteerId($volunteerId) {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Hours", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new HoursSvc();
		$hours = $svc->getByVolunteerId($volunteerId);

		$this->sendResponse(200, $hours);
	}

	public function getFamilyHoursByVolunteerId($volunteerId) {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Hours", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		//Look up the family id for this volunteer
		$volunteerSvc = new VolunteerSvc();
		$volunteer = $volunteerSvc->getById($volunteerId);

		$svc = new HoursSvc();
		$hours = $svc->getByFamilyId($volunteer->familyId);

		$this->sendResponse(200, $hours);
	}

	public function getPendingHours() {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllHours")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new HoursSvc();
		$hours = $svc->getByStatus('Pending');

		$this->sendResponse(200, $hours);
	}

	public function getApprovedTotals() {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllHours")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new HoursSvc();
		$totals = $svc->getApprovedTotals();

		$this->sendResponse(200, $totals);
	}

	public function create($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "Hours", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$hours = $this->deserializeRequestBody('Hours');
		$hours->volunteerId = $volunteerId;
		$hours->status = 'Pending';
		
		//Convert the date from miliseconds since epoch to a string that mysql can recognize
		//$millisecondsSinceEpoch = str_replace('/Date(', '', str_replace(')/', '', $hours->date));
		//$hours->date = date("Y-m-d", $millisecondsSinceEpoch / 1000);

		$svc = new HoursSvc();
		$svc->save($hours);

		$this->sendResponse(200, $hours);
	}

	public function updateStatus($hoursId) {

		$authenticatedUserId = $this->getAuthenticatedUserId();

		$svc = new HoursSvc();
		$hours = $svc->getById($hoursId);

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "Hours", $hours->volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$status = $this->getRequestBody();
		$hours->status = $status;

		$svc->save($hours);

		$this->sendResponse(200, $hours);
	}
}
