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

}
/*        public ActionResult GetWithEmailAndPassword()
        {
            SerializationMgr serializationMgr = new SerializationMgr();
            Credentials credentials = serializationMgr.Deserialize<Credentials>(HttpContext.Request.InputStream);

            VolunteerSvc volunteerSvc = new VolunteerSvc();
            Models.Volunteer volunteer = volunteerSvc.GetByEmailAddress(credentials.EmailAddress);

            if (volunteer != null)
            {
                if (!HasRight(volunteer.Id, "Create", "AccessToken"))
                    return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

                AuthenticationMgr authMgr = new AuthenticationMgr();
                string passwordHash = authMgr.CreatePasswordHash(credentials.Password, volunteer.Salt);

                if (passwordHash == volunteer.PasswordHash)
                {
                    AccessToken token = authMgr.CreateAccessToken(volunteer.Id);

                    string json = serializationMgr.Serialize(token);

                    return new ServiceResult { StatusCode = 200, Content = json };
                }
            }
            return new ServiceResult { StatusCode = 401 };
        }
*/



