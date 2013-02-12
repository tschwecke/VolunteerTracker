<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/InterestSvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class InterestController extends BaseController {

	public function getByVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Interests", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new InterestSvc();
		$interests = $svc->getByVolunteerId($volunteerId);

		$this->sendResponse(200, $interests);
	}

	public function create($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "Interests", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}
		$interest = $this->deserializeRequestBody("Interest");
		
		if($interest->volunteerId != $volunteerId) {
			$this->sendResponse(400, array('message'=>'The id in the path does not match the id in the request body.'));
		}

		$svc = new InterestSvc();
		$svc->save($interest);

		$this->sendResponse(200, $interest);
	}

	public function update($volunteerId, $interestId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "Interests", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}
		$interest = $this->deserializeRequestBody("Interest");
		
		if($interest->id != $interestId) {
			$this->sendResponse(400, array('message'=>'The interest id in the path does not match the id in the request body.'));
		}
		if($interest->volunteerId != $volunteerId) {
			$this->sendResponse(400, array('message'=>'The volunteer id in the path does not match the id in the request body.'));
		}

		$svc = new InterestSvc();
		$svc->save($interest);

		$this->sendResponse(200, $interest);
	}
}

/*
        [RequiresAuthentication]
        public ActionResult GetByVolunteerId(int volunteerId)
        {
            if (!HasRight("Read", "Interests", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            InterestSvc interestSvc = new InterestSvc();
            IList<Interest> interests = interestSvc.GetByVolunteerId(volunteerId);

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(interests);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult Create(int volunteerId)
        {
            if (!HasRight("Create", "Interests", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Interest interest = serializationMgr.Deserialize<Interest>(HttpContext.Request.InputStream);

            if (interest.VolunteerId != volunteerId) return new ServiceResult { StatusCode = 400, Content = "{\"Error\": \"The volunteer id in the path does not match the volunteer id in the request body.\"}" };

            InterestSvc interestSvc = new InterestSvc();
            interestSvc.Save(interest);

            string json = serializationMgr.Serialize(interest);
            return new ServiceResult { StatusCode = 200, Content = json };
        }

        [RequiresAuthentication]
        public ActionResult Update(int volunteerId, int interestId)
        {
            if (!HasRight("Update", "Interests", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Interest interest = serializationMgr.Deserialize<Interest>(HttpContext.Request.InputStream);

            if (interest.Id != interestId) return new ServiceResult { StatusCode = 400, Content = "{\"Error\": \"The id in the path does not match the id in the request body.\"}" };
            if (interest.VolunteerId != volunteerId) return new ServiceResult { StatusCode = 400, Content = "{\"Error\": \"The volunteer id in the path does not match the volunteer id in the request body.\"}" };

            InterestSvc interestSvc = new InterestSvc();
            interestSvc.Save(interest);

            string json = serializationMgr.Serialize(interest);
            return new ServiceResult { StatusCode = 200, Content = json };

        }
*/
