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
		
		$this->sendResponse(200, $volunteer);
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

		$newVolunteer->roleId = 1;
		$newVolunteer->salt = uniqid();

		$authenticationMgr = new AuthenticationMgr();
		$newVolunteer->passwordHash = $authenticationMgr->createPasswordHash($newVolunteer->password, $newVolunteer->salt);
		
		$svc->save($newVolunteer);

		//Clear out the salt and password hash fields before returning
		//unset($newVolunteer->salt);
		//unset($newVolunteer->passwordHash);
		

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

/*
       public ActionResult Create()
        {
            //No authentication or authorization since we don't have anyone to authorize or a role to authorize against
            //until the volunteer is created

            SerializationMgr serializationMgr = new SerializationMgr();
            NewVolunteer newVolunteer = serializationMgr.Deserialize<NewVolunteer>(HttpContext.Request.InputStream);

            Models.Volunteer existingVolunteer = getByEmailAddress(newVolunteer.EmailAddress);

            if (existingVolunteer != null) return new ServiceResult { StatusCode = 409, Content = "A volunteer with this email address has already been created" };

            Models.Volunteer volunteer = buildVolunteerFromNewVolunteer(newVolunteer);

            AuthenticationMgr authMgr = new AuthenticationMgr();
            volunteer.PasswordHash = authMgr.CreatePasswordHash(newVolunteer.Password, volunteer.Salt);

            VolunteerSvc volunteerSvc = new VolunteerSvc();
            volunteerSvc.Save(volunteer);

            //Create an access token for the new user
            AccessToken accessToken = authMgr.CreateAccessToken(volunteer.Id);

            string json = serializationMgr.Serialize(volunteer);
            return new ServiceResult { StatusCode = 200, Content = json, AccessToken = accessToken };
        }

        [RequiresAuthentication]
        public ActionResult Update(int volunteerId)
        {
            if (!HasRight("Update", "VolunteerInfo", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Models.NewVolunteer newVolunteer = serializationMgr.Deserialize<Models.NewVolunteer>(HttpContext.Request.InputStream);

            VolunteerSvc volunteerSvc = new VolunteerSvc();
            Models.Volunteer currentVolunteer = volunteerSvc.GetById(volunteerId);

            if (currentVolunteer == null) return new ServiceResult { StatusCode = 404, Content = "Could not find the volunteer to update." };

            //Copy over the fields we want to update
            currentVolunteer.FirstName = newVolunteer.FirstName;
            currentVolunteer.LastName = newVolunteer.LastName;
            currentVolunteer.EmailAddress = newVolunteer.EmailAddress;

            if (newVolunteer.Password != "UNCHANGED")
            {
                AuthenticationMgr authMgr = new AuthenticationMgr();
                currentVolunteer.PasswordHash = authMgr.CreatePasswordHash(newVolunteer.Password, currentVolunteer.Salt);
            }

            volunteerSvc.Save(currentVolunteer);

            string json = serializationMgr.Serialize(currentVolunteer);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult UpdateRole(int volunteerId)
        {
            if (!HasRight("Update", "VolunteerRole", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Models.Role role = serializationMgr.Deserialize<Models.Role>(HttpContext.Request.InputStream);

            VolunteerSvc volunteerSvc = new VolunteerSvc();
            Models.Volunteer currentVolunteer = volunteerSvc.GetById(volunteerId);

            if (currentVolunteer == null) return new ServiceResult { StatusCode = 404, Content = "Could not find the volunteer to update." };

            //Copy over the fields we want to update
            currentVolunteer.RoleId = role.Id;

            volunteerSvc.Save(currentVolunteer);

            string json = serializationMgr.Serialize(currentVolunteer);
            return new ServiceResult { StatusCode = 200, Content = json };
        }
*/

