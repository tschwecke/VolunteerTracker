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


/*
    public class InterestAreaController : ServiceBaseController
    {
        [RequiresAuthentication]
        public ActionResult GetAll()
        {
            if (!HasRight("Read", "InterestAreas"))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            InterestAreaSvc areaSvc = new InterestAreaSvc();
            IList<InterestArea> interestAreas = areaSvc.GetAll();

            IOrderedEnumerable<InterestArea> orderedInterestAreas = interestAreas.OrderBy<InterestArea, int>(i => i.SortOrder);

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(orderedInterestAreas.ToList<InterestArea>());
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult Create()
        {The id in the path does not match the id in the request body.
            if (!HasRight("Create", "InterestAreas"))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            InterestArea interestArea = serializationMgr.Deserialize<InterestArea>(HttpContext.Request.InputStream);

            InterestAreaSvc areaSvc = new InterestAreaSvc();
            areaSvc.Save(interestArea);

            string json = serializationMgr.Serialize(interestArea);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult Update(int id)
        {
            if (!HasRight("Update", "InterestAreas"))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            InterestArea interestArea = serializationMgr.Deserialize<InterestArea>(HttpContext.Request.InputStream);

            if (interestArea.Id != id) return new ServiceResult { StatusCode = 400, Content = "{\"Error\": \"The id in the path does not match the id in the request body.\"}" };

            InterestAreaSvc areaSvc = new InterestAreaSvc();
            areaSvc.Save(interestArea);


            string json = serializationMgr.Serialize(interestArea);
            return new ServiceResult { StatusCode = 200, Content = json };

        }
*/
