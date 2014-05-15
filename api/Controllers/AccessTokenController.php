<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/VolunteerSvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class AccessTokenController extends BaseController {

	public function getWithEmailAndPassword() {
		$credentials = $this->deserializeRequestBody("Credentials");

		$svc = new VolunteerSvc();
		$volunteer = $svc->getByEmailAddress($credentials->emailAddress);

		if(!is_null($volunteer)) {
			$authorizationMgr = new AuthorizationMgr();
			if (!$authorizationMgr->hasRight($volunteer->id, "Create", "AccessToken", null)) {
				$this->sendResponse(403);				
				return;
			}

			$authenticationMgr = new AuthenticationMgr();
			$passwordHash = $authenticationMgr->createPasswordHash($credentials->password, $volunteer->salt);
		
			if($passwordHash == $volunteer->passwordHash) {
				$token = $authenticationMgr->createAccessTokenFromId($volunteer->id);

				$this->sendResponse(200, $token);				
				return;
			}
		}

		//If we got to here then authentication failed
		$this->sendResponse(401);				
	}

	public function getByVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$svc = new VolunteerSvc();
		$volunteer = $svc->getById($volunteerId);

		if(!is_null($volunteer)) {
			$authorizationMgr = new AuthorizationMgr();
			if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "VolunteerInfo", $volunteerId)) {
				$this->sendResponse(403);				
				return;
			}

			$authenticationMgr = new AuthenticationMgr();
			$token = $authenticationMgr->createAccessTokenFromId($volunteerId);
			$this->sendResponse(200, $token);				
			return;
		}

		//If we got to here then the volunteer wasn't found
		$this->sendResponse(404);				
	}

}
