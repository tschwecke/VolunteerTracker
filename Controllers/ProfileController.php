<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/ProfileSvc.php';
require_once 'Util/AuthorizationMgr.php';

class ProfileController extends BaseController {

	public function getByVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Profile", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new ProfileSvc();
		$profile = $svc->getByVolunteerId($volunteerId);

		if($profile == null) {
			$this->sendResponse(404);
			return;
		}		

		$this->sendResponse(200, $profile);
	}

	public function create() {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "Profile", $authenticatedUserId)) {
			$this->sendResponse(403);				
			return;
		}

		$profile = $this->deserializeRequestBody("Profile");
		$svc = new ProfileSvc();

		if($profile->familyId == -1) {
			$profile->familyId = $this->getAvailableFamilyId();
		}
		else {
			if(count($svc->getByFamilyId($profile->familyId)) == 0) {
				$this->sendResponse(400, array('message'=>'The provided Family Id does not exist.'));
				return;
			}
		}

		$svc->save($profile);
 
		$this->sendResponse(200, $profile);
	}

	public function update($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "Profile", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$profile = $this->deserializeRequestBody("Profile");
		$svc = new ProfileSvc();

		if ($profile->volunteerId != $volunteerId) {
			$this->sendResponse(400, array('message'=>'The volunteer id in the path does not match the id in the request body.'));	
			return;
		}	

		$currentProfile = $svc->getByVolunteerId($volunteerId);

		$profile->id = $currentProfile->id;
		$profile->familyId = $currentProfile->familyId;

		$svc->save($profile);

		$this->sendResponse(200, $profile);
	}

	private function getAvailableFamilyId() {
		$familyId = 0;
		$attempts = 0;
		$found = false;
		$svc = new ProfileSvc();

		while (!$found)
		{
			$familyId = rand(100000, 999999);
			$profiles = $svc->getByFamilyId($familyId);

			if (count($profiles) == 0)
                	{
                    		$found = true;
                	}

                	$attempts++;

	                if($attempts > 20) 
	                {
				$familyId = -999999;
				$found = true;
			}
		}

		return $familyId;
	}
}

/*
   public class ProfileController : ServiceBaseController
    {
        [RequiresAuthentication]
        public ActionResult GetByVolunteerId(int volunteerId)
        {
            if (!HasRight("Read", "Profile", volunteerId)) 
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            ProfileSvc profileSvc = new ProfileSvc();
            Profile profile = profileSvc.GetByVolunteerId(volunteerId);

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(profile);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult Create(int volunteerId)
        {
            if (!HasRight("Create", "Profile", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Profile profile = serializationMgr.Deserialize<Profile>(HttpContext.Request.InputStream);
            ProfileSvc profileSvc = new ProfileSvc();
            profile.VolunteerId = volunteerId;

            if (profile.FamilyId == -1)
            {
                profile.FamilyId = getAvailableFamilyId();
            }
            else
            {
                if (profileSvc.GetByFamilyId(profile.FamilyId).Count == 0)
                {
                    return new ServiceResult { StatusCode = 400, Content = "The provided Family Id does not exist." };
                }
            }

            profileSvc.Save(profile);


            string json = serializationMgr.Serialize(profile);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        private int getAvailableFamilyId()
        {
            Random random = new Random();
            int familyId = 0;
            int attempts = 0;
            bool found = false;
            ProfileSvc profileSvc = new ProfileSvc();

            while (!found)
            {
                familyId = random.Next(0, 999999);
                IList<Profile> profiles = profileSvc.GetByFamilyId(familyId);

                if (profiles.Count == 0)
                {
                    found = true;
                }

                attempts++;

                if(attempts > 20) 
                {
                    familyId = -999999;
                    found = true;
                }
            }

            return familyId;
        }

        [RequiresAuthentication]
        public ActionResult Update(int volunteerId)
        {
            if (!HasRight("Update", "Profile", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Profile profile = serializationMgr.Deserialize<Profile>(HttpContext.Request.InputStream);

            if (profile.VolunteerId != volunteerId) return new ServiceResult { StatusCode = 400, Content = "{\"Error\": \"The volunteer id in the path does not match the id in the request body.\"}" };

            ProfileSvc profileSvc = new ProfileSvc();
            Profile currentProfile = profileSvc.GetByVolunteerId(volunteerId);

            profile.Id = currentProfile.Id;
            profile.FamilyId = currentProfile.FamilyId;

            profileSvc.Save(profile);

            string json = serializationMgr.Serialize(profile);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

    }

*/
