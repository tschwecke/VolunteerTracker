<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/KioskCheckInSvc.php';
require_once 'Domain/HoursSvc.php';
require_once 'Util/AuthorizationMgr.php';

class KioskCheckInController extends BaseController {

	public function getById($id) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "KioskCheckIn")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new KioskCheckInSvc();
		$checkin = $svc->getById($id);
		
		$this->sendResponse(200, $checkin);
	}

	public function getActive() {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "KioskCheckIn")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new KioskCheckInSvc();
		$checkins = $svc->getActive();
		
		$this->sendResponse(200, $checkins);
	}

	public function create() {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "KioskCheckIn")) {
			$this->sendResponse(403);				
			return;
		}

		$newCheckIn = $this->deserializeRequestBody("KioskCheckIn");

		$svc = new KioskCheckInSvc();

		//Make sure this user hasn't already checked in and not checked out
		$checkins = $svc->getActive();

		for($i=0; $i<count($checkins); $i++) {
			if($checkins[$i]->volunteerId == $newCheckIn->volunteerId) {
				$this->sendResponse(409, array(code=>"DuplicateCheckIn", message=>"This user has already checked in and not checked out."));
				return;
			}
		}

		//Set the checkin time and save
		$newCheckIn->checkInTime = $this->getCurrentTime();

		$svc->save($newCheckIn);

		$this->sendResponse(200, $newCheckIn);
	}

	public function checkOut($checkInId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "KioskCheckIn")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new KioskCheckInSvc();
		$checkIn = $svc->getById($checkInId);

		if(!is_null($checkIn->checkOutTime)) {
			$this->sendResponse(409, array(message=>"This record has already been checked out."));
			return;
		}

		$checkIn->checkOutTime = $this->getCurrentTime();
		$svc->save($checkIn);

		//Determine the number of hours to grant based on the check in check out times
		$checkInTime = new DateTime($checkIn->checkInTime);
		$checkOutTime = new DateTime($checkIn->checkOutTime);
		$diff = date_diff($checkOutTime, $checkInTime);
		$minutes = $this->roundOffMinutes($diff->i);
		$checkIn->nbrHours = $diff->h + ($minutes / 60);

		//Save the hours
		$dateString = $checkOutTime->format("Y-m-d");
		$hoursSvc = new HoursSvc();
		$hourProperties = array("volunteerId"=> $checkIn->volunteerId,
			"interestAreaId"=>$checkIn->interestAreaId,
			"date"=>$dateString,
			"nbrOfHours"=>$checkIn->nbrHours,
			"description"=>"Auto-Approved via Kiosk",
			"status"=>"Approved",
			"classroom"=>$checkIn->classroom);
		$hours=new DomainObject("Hours", $hourProperties);

		$hoursSvc->save($hours);

		$this->sendResponse(200, $checkIn);
	}

	private function getCurrentTime() {
		date_default_timezone_set("UTC");
		return date("Y-m-d\TH:i:s\Z", time()); 
	}

	private function roundOffMinutes($rawMinutes) {
		if($rawMinutes < 7) {
			$roundedMinutes = 0;
		}
		else if($rawMinutes < 22) {
			$roundedMinutes = 15;
		}
		else if($rawMinutes < 37) {
			$roundedMinutes = 30;
		}
		else if($rawMinutes < 52) {
			$roundedMinutes = 45;
		}
		else {
			$roundedMinutes = 60;
		}

		return $roundedMinutes;
	}
}