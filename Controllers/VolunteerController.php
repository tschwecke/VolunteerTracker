<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/VolunteerSvc.php';
require_once 'Util/AuthorizationMgr.php';

class VolunteerController extends BaseController {

	public function getById($id) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "VolunteerInfo", $id)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new VolunteerSvc();
		$volunteer = $svc->getById($id);

		//Clear out the salt and password hash fields before returning
		unset($volunteer->salt);
		unset($volunteer->passwordHash);
		
		$this->sendResponse(200, $volunteer);
	}

	public function getByInterestAreaId($interestAreaId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllVolunteerInfo", $id)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new VolunteerSvc();
		$volunteers = $svc->getByInterestAreaId($interestAreaId);


		//Clear out the salt and password hash fields before returning
		for($i=0; $i<count($volunteers); $i++) {
			$volunteer = $volunteers[$i];
			unset($volunteer->salt);
			unset($volunteer->passwordHash);
		}

		$this->sendResponse(200, $volunteers);
	}

	public function getAll() {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllVolunteerInfo")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new VolunteerSvc();
		$volunteers = $svc->getAll();

		//Clear out the salt and password hash fields before returning
		for($i=0; $i<count($volunteers); $i++) {
			$volunteer = $volunteers[$i];
			unset($volunteer->salt);
			unset($volunteer->passwordHash);
		}

		$this->sendResponse(200, $volunteers);
	}

	public function create() {
		$newVolunteer = $this->deserializeRequestBody("Volunteer");

		$svc = new VolunteerSvc();
		$existingVolunteer = $svc->getByEmailAddress($newVolunteer->emailAddress);
		
		if(!is_null($existingVolunteer)) {
			$this->sendResponse(409, array('message'=>'A volunteer with this email address has already been created'));
			return;
		}

		$newVolunteer->roleId = 2;
		$newVolunteer->salt = uniqid();

		$authenticationMgr = new AuthenticationMgr();
		$newVolunteer->passwordHash = $authenticationMgr->createPasswordHash($newVolunteer->password, $newVolunteer->salt);
		
		$svc->save($newVolunteer);

		//Clear out the salt and password hash fields before returning
		unset($newVolunteer->salt);
		unset($newVolunteer->passwordHash);
		

		//Create an access token to send back
		$authenticationMgr = new AuthenticationMgr();
		$accessToken = $authenticationMgr->createAccessTokenFromId($newVolunteer->id);

		$this->sendResponse(200, $accessToken);
	}

	public function update($id) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "VolunteerInfo", $id)) {
			$this->sendResponse(403);				
			return;
		}

		$newVolunteer = $this->deserializeRequestBody("Volunteer");

		$svc = new VolunteerSvc();
		$currentVolunteer = $svc->getById($id);

		if($currentVolunteer == null) {
			$this->sendResponse(404, array('message'=>'Could not find the volunteer to update.'));
			return;
		}

		//Copy over the fields we want to update
		$currentVolunteer->firstName = $newVolunteer->firstName;
		$currentVolunteer->lastName = $newVolunteer->lastName;
		$currentVolunteer->emailAddress = $newVolunteer->emailAddress;
		$currentVolunteer->familyId = $newVolunteer->familyId;
		$currentVolunteer->primaryPhoneNbr = $newVolunteer->primaryPhoneNbr;

		if($newVolunteer->password != 'UNCHANGED') {
			$authenticationMgr = new AuthenticationMgr();
			$currentVolunteer->passwordHash = $authenticationMgr->createPasswordHash($newVolunteer->password, $currentVolunteer->salt);
		}

		$svc->save($currentVolunteer);

		//Clear out the salt and password hash fields before returning
		unset($currentVolunteer->salt);
		unset($currentVolunteer->passwordHash);
		
		$this->sendResponse(200, $currentVolunteer);
	}

	public function updateRole($id) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "VolunteerRole", $id)) {
			$this->sendResponse(403);				
			return;
		}

		$role = $this->deserializeRequestBody("Role");

		$svc = new VolunteerSvc();
		$currentVolunteer = $svc->getById($id);

		if($currentVolunteer == null) {
			$this->sendResponse(404, array('message'=>'Could not find the volunteer to update.'));
			return;
		}

		//Copy over the fields we want to update
		$currentVolunteer->roleId = $role->id;

		$svc->save($currentVolunteer);

		//Clear out the salt and password hash fields before returning
		unset($currentVolunteer->salt);
		unset($currentVolunteer->passwordHash);
		
		$this->sendResponse(200, $currentVolunteer);
	}

}

