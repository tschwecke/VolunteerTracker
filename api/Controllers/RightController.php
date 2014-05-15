<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/RightSvc.php';

class RightController extends BaseController {

	public function getByRoleId($roleId) {
		$svc = new RightSvc();
		$rights = $svc->getByRoleId($roleId);

		$this->sendResponse(200, $rights);
	}

}

/*
        [RequiresAuthentication]
        public ActionResult GetByRoleId(int roleId)
        {
            RightSvc rightSvc = new RightSvc();
            IList<Right> rights = rightSvc.GetByRoleId(roleId); 

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(rights);
            return new ServiceResult { StatusCode = 200, Content = json };
        }
*/
